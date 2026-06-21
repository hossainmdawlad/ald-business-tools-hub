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
        <div class="bth-color-picker-wrap">
            <input class="bth-color-input" type="color" id="bth-color-base" value="#D60000">
        </div>
        <div class="bth-color-formats">
            <div class="bth-color-format-row">
                <label class="bth-color-format-label">HEX</label>
                <input class="bth-form-input bth-color-format-input" type="text" id="bth-color-hex" value="#D60000" maxlength="7" placeholder="#000000">
            </div>
            <div class="bth-color-format-row">
                <label class="bth-color-format-label">RGB</label>
                <input class="bth-form-input bth-color-format-input" type="text" id="bth-color-rgb" value="rgb(214, 0, 0)" placeholder="rgb(0, 0, 0)">
            </div>
            <div class="bth-color-format-row">
                <label class="bth-color-format-label">RGBA</label>
                <input class="bth-form-input bth-color-format-input" type="text" id="bth-color-rgba" value="rgba(214, 0, 0, 1)" placeholder="rgba(0, 0, 0, 1)">
            </div>
            <div class="bth-color-format-row">
                <label class="bth-color-format-label">CMYK</label>
                <input class="bth-form-input bth-color-format-input" type="text" id="bth-color-cmyk" value="cmyk(0%, 100%, 100%, 17%)" placeholder="cmyk(0%, 0%, 0%, 0%)">
            </div>
        </div>
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

    var baseInput    = document.getElementById('bth-color-base');
    var hexInput     = document.getElementById('bth-color-hex');
    var rgbInput     = document.getElementById('bth-color-rgb');
    var rgbaInput    = document.getElementById('bth-color-rgba');
    var cmykInput    = document.getElementById('bth-color-cmyk');
    var typeSelect   = document.getElementById('bth-color-type');
    var btnGenerate  = document.getElementById('bth-color-generate');
    var palette      = document.getElementById('bth-color-palette');
    var exportWrap   = document.getElementById('bth-color-export-wrap');
    var btnExportCss = document.getElementById('bth-color-export-css');
    var btnExportJson = document.getElementById('bth-color-export-json');
    var outputWrap   = document.getElementById('bth-color-output-wrap');
    var outputArea   = document.getElementById('bth-color-output');

    var currentPalette = [];

    // ---- Sync all format inputs ----

    function updateAllFromHex(hex) {
        hex = hex.toUpperCase();
        var rgb = hexToRgb(hex);
        var rgba = rgbToRgba(rgb.r, rgb.g, rgb.b, 1);
        var cmyk = rgbToCmyk(rgb.r, rgb.g, rgb.b);
        baseInput.value = hex.toLowerCase();
        hexInput.value = hex;
        rgbInput.value = 'rgb(' + rgb.r + ', ' + rgb.g + ', ' + rgb.b + ')';
        rgbaInput.value = rgba;
        cmykInput.value = cmykStr(cmyk);
    }

    function parseRgb(str) {
        var m = str.match(/rgb\s*\(\s*(\d{1,3})\s*,\s*(\d{1,3})\s*,\s*(\d{1,3})\s*\)/i);
        if (!m) return null;
        return { r: parseInt(m[1]), g: parseInt(m[2]), b: parseInt(m[3]) };
    }

    function parseRgba(str) {
        var m = str.match(/rgba\s*\(\s*(\d{1,3})\s*,\s*(\d{1,3})\s*,\s*(\d{1,3})\s*,\s*([0-9.]+)\s*\)/i);
        if (!m) return null;
        return { r: parseInt(m[1]), g: parseInt(m[2]), b: parseInt(m[3]), a: parseFloat(m[4]) };
    }

    function parseCmyk(str) {
        var m = str.match(/cmyk\s*\(\s*(\d{1,3})%\s*,\s*(\d{1,3})%\s*,\s*(\d{1,3})%\s*,\s*(\d{1,3})%\s*\)/i);
        if (!m) return null;
        return { c: parseInt(m[1]), m: parseInt(m[2]), y: parseInt(m[3]), k: parseInt(m[4]) };
    }

    function rgbToHex(r, g, b) {
        return '#' + [r, g, b].map(function (v) {
            return Math.max(0, Math.min(255, v)).toString(16).padStart(2, '0');
        }).join('');
    }

    function cmykToRgb(c, m, y, k) {
        c /= 100; m /= 100; y /= 100; k /= 100;
        return {
            r: Math.round(255 * (1 - c) * (1 - k)),
            g: Math.round(255 * (1 - m) * (1 - k)),
            b: Math.round(255 * (1 - y) * (1 - k))
        };
    }

    // Color picker → all fields
    baseInput.addEventListener('input', function () {
        updateAllFromHex(baseInput.value);
    });

    // Hex input → all fields
    hexInput.addEventListener('input', function () {
        var hex = hexInput.value.trim();
        if (/^#[0-9A-Fa-f]{6}$/.test(hex)) {
            updateAllFromHex(hex);
        }
    });

    // RGB input → all fields
    rgbInput.addEventListener('blur', function () {
        var rgb = parseRgb(rgbInput.value);
        if (rgb) {
            updateAllFromHex(rgbToHex(rgb.r, rgb.g, rgb.b));
        }
    });

    // RGBA input → all fields
    rgbaInput.addEventListener('blur', function () {
        var rgba = parseRgba(rgbaInput.value);
        if (rgba) {
            updateAllFromHex(rgbToHex(rgba.r, rgba.g, rgba.b));
        }
    });

    // CMYK input → all fields
    cmykInput.addEventListener('blur', function () {
        var cmyk = parseCmyk(cmykInput.value);
        if (cmyk) {
            var rgb = cmykToRgb(cmyk.c, cmyk.m, cmyk.y, cmyk.k);
            updateAllFromHex(rgbToHex(rgb.r, rgb.g, rgb.b));
        }
    });

    // Init
    updateAllFromHex(baseInput.value);

    // ---- Color conversion helpers ----

    function hexToRgb(hex) {
        var r = parseInt(hex.substr(1, 2), 16);
        var g = parseInt(hex.substr(3, 2), 16);
        var b = parseInt(hex.substr(5, 2), 16);
        return { r: r, g: g, b: b };
    }

    function rgbToRgba(r, g, b, a) {
        if (a === undefined) a = 1;
        return 'rgba(' + r + ', ' + g + ', ' + b + ', ' + a + ')';
    }

    function rgbToCmyk(r, g, b) {
        var rf = r / 255;
        var gf = g / 255;
        var bf = b / 255;
        var k = 1 - Math.max(rf, gf, bf);
        if (k >= 1) return { c: 0, m: 0, y: 0, k: 100 };
        var c = (1 - rf - k) / (1 - k);
        var m = (1 - gf - k) / (1 - k);
        var y = (1 - bf - k) / (1 - k);
        return {
            c: Math.round(c * 100),
            m: Math.round(m * 100),
            y: Math.round(y * 100),
            k: Math.round(k * 100)
        };
    }

    function cmykStr(cmyk) {
        return 'cmyk(' + cmyk.c + '%, ' + cmyk.m + '%, ' + cmyk.y + '%, ' + cmyk.k + '%)';
    }

    function hexToHsl(hex) {
        var r = parseInt(hex.substr(1, 2), 16) / 255;
        var g = parseInt(hex.substr(3, 2), 16) / 255;
        var b = parseInt(hex.substr(5, 2), 16) / 255;

        var max = Math.max(r, g, b);
        var min = Math.min(r, g, b);
        var h, s, l = (max + min) / 2;

        if (max === min) {
            h = 0; s = 0;
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

    function getAllFormats(hex) {
        var rgb = hexToRgb(hex);
        var rgba = rgbToRgba(rgb.r, rgb.g, rgb.b, 1);
        var cmyk = rgbToCmyk(rgb.r, rgb.g, rgb.b);
        return {
            hex: hex.toUpperCase(),
            rgba: rgba,
            cmyk: cmykStr(cmyk)
        };
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
        var expanded = [];
        for (var i = 0; i < colors.length; i++) {
            expanded.push(colors[i]);
            if (expanded.length < 6) {
                expanded.push({ h: colors[i].h, s: colors[i].s, l: Math.min(95, colors[i].l + 20) });
            }
        }
        return expanded.slice(0, 6);
    }

    function generatePalette(hex, type) {
        var hsl = hexToHsl(hex);
        var colors;
        switch (type) {
            case 'complementary':       colors = generateComplementary(hsl); break;
            case 'triadic':             colors = generateTriadic(hsl); break;
            case 'analogic':            colors = generateAnalogic(hsl); break;
            case 'split-complementary': colors = generateSplitComplementary(hsl); break;
            case 'monochromatic':       colors = generateMonochromatic(hsl); break;
            default:                    colors = generateComplementary(hsl);
        }
        colors = expandToSix(colors);
        return colors.map(function (c) {
            var hex = hslToHex(c.h, c.s, c.l);
            return getAllFormats(hex);
        });
    }

    // ---- Render ----

    function renderPalette(colorData) {
        currentPalette = colorData;
        palette.innerHTML = '';

        colorData.forEach(function (data, index) {
            var swatch = document.createElement('div');
            swatch.className = 'bth-color-swatch';
            swatch.setAttribute('data-hex', data.hex);
            swatch.setAttribute('role', 'button');
            swatch.setAttribute('tabindex', '0');
            swatch.setAttribute('aria-label', data.hex);

            var preview = document.createElement('div');
            preview.className = 'bth-color-swatch-preview';
            preview.style.backgroundColor = data.hex;
            swatch.appendChild(preview);

            var hexLabel = document.createElement('span');
            hexLabel.className = 'bth-color-hex';
            hexLabel.textContent = data.hex;
            swatch.appendChild(hexLabel);

            var rgbaLabel = document.createElement('span');
            rgbaLabel.className = 'bth-color-rgba';
            rgbaLabel.textContent = data.rgba;
            swatch.appendChild(rgbaLabel);

            var cmykLabel = document.createElement('span');
            cmykLabel.className = 'bth-color-cmyk';
            cmykLabel.textContent = data.cmyk;
            swatch.appendChild(cmykLabel);

            palette.appendChild(swatch);

            // Click to copy all formats
            var doCopy = function () {
                var text = data.hex + ' | ' + data.rgba + ' | ' + data.cmyk;
                copyToClipboard(text, swatch);
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
        } catch (e) {}
        document.body.removeChild(ta);
    }

    function showCopied(el) {
        var labels = el.querySelectorAll('span');
        var originals = [];
        labels.forEach(function (l) { originals.push(l.textContent); });
        labels[0].textContent = '<?php echo esc_js( __( 'Copied!', 'ald-business-tools' ) ); ?>';
        for (var i = 1; i < labels.length; i++) { labels[i].textContent = ''; }
        el.style.outline = '3px solid #4CAF50';
        setTimeout(function () {
            labels.forEach(function (l, i) { l.textContent = originals[i]; });
            el.style.outline = '';
        }, 1200);
    }

    // ---- Export ----

    function exportCSS() {
        var css = ':root {\n';
        currentPalette.forEach(function (data, i) {
            css += '  --color-' + (i + 1) + ': ' + data.hex + ';\n';
            css += '  --color-' + (i + 1) + '-rgba: ' + data.rgba + ';\n';
            css += '  --color-' + (i + 1) + '-cmyk: ' + data.cmyk + ';\n';
        });
        css += '}\n';
        outputArea.value = css;
        outputWrap.style.display = 'block';
    }

    function exportJSON() {
        var obj = {};
        currentPalette.forEach(function (data, i) {
            obj['color-' + (i + 1)] = {
                hex: data.hex,
                rgba: data.rgba,
                cmyk: data.cmyk
            };
        });
        outputArea.value = JSON.stringify(obj, null, 2);
        outputWrap.style.display = 'block';
    }

    // ---- Event listeners ----

    btnGenerate.addEventListener('click', function () {
        // Resolve hex from whichever input was last used
        var hex = baseInput.value;
        var hexVal = hexInput.value.trim();
        var rgbVal = rgbInput.value.trim();
        var rgbaVal = rgbaInput.value.trim();
        var cmykVal = cmykInput.value.trim();

        if (/^#[0-9A-Fa-f]{6}$/.test(hexVal)) {
            hex = hexVal;
        } else {
            var rgb = parseRgb(rgbVal) || parseRgba(rgbaVal);
            if (rgb) {
                hex = rgbToHex(rgb.r, rgb.g, rgb.b);
            } else {
                var cmyk = parseCmyk(cmykVal);
                if (cmyk) {
                    var rgb2 = cmykToRgb(cmyk.c, cmyk.m, cmyk.y, cmyk.k);
                    hex = rgbToHex(rgb2.r, rgb2.g, rgb2.b);
                }
            }
        }

        // Sync all inputs to resolved hex
        updateAllFromHex(hex);

        var type = typeSelect.value;
        var colors = generatePalette(hex, type);
        renderPalette(colors);
        outputWrap.style.display = 'none';
    });

    btnExportCss.addEventListener('click', exportCSS);
    btnExportJson.addEventListener('click', exportJSON);

})();
</script>
