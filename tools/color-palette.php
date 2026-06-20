<?php
/**
 * Color Palette Generator Tool
 *
 * @package ALD_Business_Tools_Hub
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}
?>
<div class="bth-color-wrap">
    <div class="bth-form-group">
        <label class="bth-form-label" for="bth-color-base"><?php esc_html_e( 'Base Color', 'ald-business-tools' ); ?></label>
        <input class="bth-form-input" type="color" id="bth-color-base" value="#D60000">
    </div>

    <div class="bth-form-group">
        <label class="bth-form-label" for="bth-color-type"><?php esc_html_e( 'Palette Type', 'ald-business-tools' ); ?></label>
        <select class="bth-form-select" id="bth-color-type">
            <option value="complementary"><?php esc_html_e( 'Complementary', 'ald-business-tools' ); ?></option>
            <option value="triadic"><?php esc_html_e( 'Triadic', 'ald-business-tools' ); ?></option>
            <option value="analogic"><?php esc_html_e( 'Analogic', 'ald-business-tools' ); ?></option>
            <option value="split-complementary"><?php esc_html_e( 'Split-Complementary', 'ald-business-tools' ); ?></option>
            <option value="monochromatic"><?php esc_html_e( 'Monochromatic', 'ald-business-tools' ); ?></option>
        </select>
    </div>

    <div class="bth-form-group">
        <button type="button" class="bth-btn bth-btn-primary" id="bth-color-generate"><?php esc_html_e( 'Generate Palette', 'ald-business-tools' ); ?></button>
    </div>

    <div class="bth-color-palette" id="bth-color-palette"></div>

    <div class="bth-form-group" id="bth-color-export-wrap" style="display:none;">
        <button type="button" class="bth-btn bth-btn-secondary" id="bth-color-export-css"><?php esc_html_e( 'Export as CSS Variables', 'ald-business-tools' ); ?></button>
        <button type="button" class="bth-btn bth-btn-secondary" id="bth-color-export-json"><?php esc_html_e( 'Export as JSON', 'ald-business-tools' ); ?></button>
    </div>

    <div class="bth-form-group" id="bth-color-output-wrap" style="display:none;">
        <textarea class="bth-form-textarea" id="bth-color-output" rows="8" readonly></textarea>
    </div>
</div>

<script>
(function () {
    'use strict';

    var baseInput   = document.getElementById('bth-color-base');
    var typeSelect  = document.getElementById('bth-color-type');
    var btnGenerate = document.getElementById('bth-color-generate');
    var palette     = document.getElementById('bth-color-palette');
    var exportWrap  = document.getElementById('bth-color-export-wrap');
    var btnExportCss = document.getElementById('bth-color-export-css');
    var btnExportJson = document.getElementById('bth-color-export-json');
    var outputWrap  = document.getElementById('bth-color-output-wrap');
    var outputArea  = document.getElementById('bth-color-output');

    var currentPalette = [];

    // ---- HSL helpers ----

    function hexToHsl(hex) {
        var r = parseInt(hex.substr(1, 2), 16) / 255;
        var g = parseInt(hex.substr(3, 2), 16) / 255;
        var b = parseInt(hex.substr(5, 2), 16) / 255;

        var max = Math.max(r, g, b);
        var min = Math.min(r, g, b);
        var h, s, l = (max + min) / 2;

        if (max === min) {
            h = 0;
            s = 0;
        } else {
            var d = max - min;
            s = l > 0.5 ? d / (2 - max - min) : d / (max + min);
            switch (max) {
                case r: h = ((g - b) / d + (g < b ? 6 : 0)) / 6; break;
                case g: h = ((b - r) / d + 2) / 6; break;
                case b: h = ((r - g) / d + 4) / 6; break;
            }
        }

        return { h: Math.round(h * 360), s: Math.round(s * 100), l: Math.round(l * 100) };
    }

    function hslToHex(h, s, l) {
        h = ((h % 360) + 360) % 360;
        s = Math.max(0, Math.min(100, s)) / 100;
        l = Math.max(0, Math.min(100, l)) / 100;

        var c = (1 - Math.abs(2 * l - 1)) * s;
        var x = c * (1 - Math.abs((h / 60) % 2 - 1));
        var m = l - c / 2;
        var r = 0, g = 0, b = 0;

        if (h < 60)       { r = c; g = x; b = 0; }
        else if (h < 120) { r = x; g = c; b = 0; }
        else if (h < 180) { r = 0; g = c; b = x; }
        else if (h < 240) { r = 0; g = x; b = c; }
        else if (h < 300) { r = x; g = 0; b = c; }
        else              { r = c; g = 0; b = x; }

        r = Math.round((r + m) * 255);
        g = Math.round((g + m) * 255);
        b = Math.round((b + m) * 255);

        return '#' + [r, g, b].map(function (v) {
            return v.toString(16).padStart(2, '0');
        }).join('');
    }

    // ---- Palette generators ----

    function generateComplementary(hsl) {
        return [
            hsl,
            { h: (hsl.h + 180) % 360, s: hsl.s, l: hsl.l }
        ];
    }

    function generateTriadic(hsl) {
        return [
            hsl,
            { h: (hsl.h + 120) % 360, s: hsl.s, l: hsl.l },
            { h: (hsl.h + 240) % 360, s: hsl.s, l: hsl.l }
        ];
    }

    function generateAnalogic(hsl) {
        return [
            { h: (hsl.h - 30 + 360) % 360, s: hsl.s, l: hsl.l },
            hsl,
            { h: (hsl.h + 30) % 360, s: hsl.s, l: hsl.l }
        ];
    }

    function generateSplitComplementary(hsl) {
        return [
            hsl,
            { h: (hsl.h + 150) % 360, s: hsl.s, l: hsl.l },
            { h: (hsl.h + 210) % 360, s: hsl.s, l: hsl.l }
        ];
    }

    function generateMonochromatic(hsl) {
        return [
            { h: hsl.h, s: hsl.s, l: Math.max(10, hsl.l - 30) },
            { h: hsl.h, s: hsl.s, l: Math.max(20, hsl.l - 15) },
            hsl,
            { h: hsl.h, s: hsl.s, l: Math.min(90, hsl.l + 15) },
            { h: hsl.h, s: hsl.s, l: Math.min(95, hsl.l + 30) }
        ];
    }

    function expandToSix(colors) {
        if (colors.length >= 6) return colors.slice(0, 6);
        // For palettes with fewer colors, add lightness variations
        var expanded = [];
        for (var i = 0; i < colors.length; i++) {
            expanded.push(colors[i]);
            if (expanded.length < 6) {
                expanded.push({
                    h: colors[i].h,
                    s: colors[i].s,
                    l: Math.min(95, colors[i].l + 20)
                });
            }
        }
        return expanded.slice(0, 6);
    }

    function generatePalette(hex, type) {
        var hsl = hexToHsl(hex);
        var colors;

        switch (type) {
            case 'complementary':      colors = generateComplementary(hsl); break;
            case 'triadic':            colors = generateTriadic(hsl); break;
            case 'analogic':           colors = generateAnalogic(hsl); break;
            case 'split-complementary': colors = generateSplitComplementary(hsl); break;
            case 'monochromatic':      colors = generateMonochromatic(hsl); break;
            default:                   colors = generateComplementary(hsl);
        }

        colors = expandToSix(colors);

        return colors.map(function (c) {
            return hslToHex(c.h, c.s, c.l);
        });
    }

    // ---- Render ----

    function renderPalette(hexColors) {
        currentPalette = hexColors;
        palette.innerHTML = '';

        hexColors.forEach(function (hex, index) {
            var swatch = document.createElement('div');
            swatch.className = 'bth-color-swatch';
            swatch.style.backgroundColor = hex;
            swatch.setAttribute('data-hex', hex);
            swatch.setAttribute('role', 'button');
            swatch.setAttribute('tabindex', '0');
            swatch.setAttribute('aria-label', hex);

            var hexLabel = document.createElement('span');
            hexLabel.className = 'bth-color-hex';
            hexLabel.textContent = hex.toUpperCase();

            swatch.appendChild(hexLabel);
            palette.appendChild(swatch);

            // Click to copy
            var doCopy = function () {
                copyToClipboard(hex, swatch);
            };
            swatch.addEventListener('click', doCopy);
            swatch.addEventListener('keydown', function (e) {
                if (e.key === 'Enter' || e.key === ' ') {
                    e.preventDefault();
                    doCopy();
                }
            });
        });

        exportWrap.style.display = 'block';
    }

    function copyToClipboard(text, swatchEl) {
        if (navigator.clipboard && navigator.clipboard.writeText) {
            navigator.clipboard.writeText(text).then(function () {
                showCopied(swatchEl);
            }).catch(function () {
                fallbackCopy(text, swatchEl);
            });
        } else {
            fallbackCopy(text, swatchEl);
        }
    }

    function fallbackCopy(text, swatchEl) {
        var ta = document.createElement('textarea');
        ta.value = text;
        ta.style.position = 'fixed';
        ta.style.left = '-9999px';
        document.body.appendChild(ta);
        ta.select();
        try {
            document.execCommand('copy');
            showCopied(swatchEl);
        } catch (e) {
            // silently fail
        }
        document.body.removeChild(ta);
    }

    function showCopied(el) {
        var original = el.querySelector('.bth-color-hex').textContent;
        var label = el.querySelector('.bth-color-hex');
        label.textContent = '<?php echo esc_js( __( 'Copied!', 'ald-business-tools' ) ); ?>';
        el.style.outline = '3px solid #4CAF50';
        setTimeout(function () {
            label.textContent = original;
            el.style.outline = '';
        }, 1200);
    }

    // ---- Export ----

    function exportCSS() {
        var css = ':root {\n';
        currentPalette.forEach(function (hex, i) {
            css += '  --color-' + (i + 1) + ': ' + hex + ';\n';
        });
        css += '}\n';
        outputArea.value = css;
        outputWrap.style.display = 'block';
    }

    function exportJSON() {
        var obj = {};
        currentPalette.forEach(function (hex, i) {
            obj['color-' + (i + 1)] = hex;
        });
        outputArea.value = JSON.stringify(obj, null, 2);
        outputWrap.style.display = 'block';
    }

    // ---- Event listeners ----

    btnGenerate.addEventListener('click', function () {
        var hex   = baseInput.value;
        var type  = typeSelect.value;
        var colors = generatePalette(hex, type);
        renderPalette(colors);
        outputWrap.style.display = 'none';
    });

    btnExportCss.addEventListener('click', exportCSS);
    btnExportJson.addEventListener('click', exportJSON);

})();
</script>
