<?php
/**
 * Image Converter Tool
 * Convert between JPG, PNG, WebP, BMP, GIF formats
 *
 * @package ALD_Business_Tools_Hub
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

$formats = array(
    'image/jpeg' => 'JPG',
    'image/png'  => 'PNG',
    'image/webp' => 'WebP',
    'image/bmp'  => 'BMP',
    'image/gif'  => 'GIF',
);
?>
<div class="bth-image-wrap">
    <div class="bth-dropzone" id="bth-convert-dropzone">
        <div class="bth-dropzone-icon">
            <svg xmlns="http://www.w3.org/2000/svg" width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><polyline points="17 8 12 3 7 8"/><line x1="12" y1="3" x2="12" y2="15"/></svg>
        </div>
        <p class="bth-dropzone-text"><?php esc_html_e( 'Drag & drop an image here, or click to browse', 'ald-business-tools' ); ?></p>
        <p class="bth-dropzone-hint"><?php esc_html_e( 'Supports JPG, PNG, WebP, GIF, BMP', 'ald-business-tools' ); ?></p>
        <input type="file" id="bth-convert-input" accept="image/*" class="bth-dropzone-input">
    </div>

    <div class="bth-form-group">
        <label class="bth-form-label" for="bth-convert-format"><?php esc_html_e( 'Convert To', 'ald-business-tools' ); ?></label>
        <select class="bth-form-select" id="bth-convert-format">
            <?php foreach ( $formats as $mime => $label ) : ?>
                <option value="<?php echo esc_attr( $mime ); ?>"><?php echo esc_html( $label ); ?></option>
            <?php endforeach; ?>
        </select>
    </div>

    <div class="bth-form-group">
        <label class="bth-form-label" for="bth-convert-quality"><?php esc_html_e( 'Quality', 'ald-business-tools' ); ?>: <span id="bth-convert-quality-value">80</span>%</label>
        <input class="bth-form-input" type="range" id="bth-convert-quality" min="10" max="100" value="80">
    </div>

    <div class="bth-form-group bth-form-actions">
        <button type="button" class="bth-btn bth-btn-primary" id="bth-convert-btn" disabled><?php esc_html_e( 'Convert Image', 'ald-business-tools' ); ?></button>
        <button type="button" class="bth-btn bth-btn-secondary" id="bth-convert-reset"><?php esc_html_e( 'Reset', 'ald-business-tools' ); ?></button>
    </div>

    <div class="bth-result-box" id="bth-convert-result" style="display:none;">
        <div class="bth-image-preview">
            <div class="bth-preview-panel">
                <h4><?php esc_html_e( 'Original', 'ald-business-tools' ); ?></h4>
                <canvas id="bth-convert-original-canvas"></canvas>
                <p class="bth-preview-size" id="bth-convert-original-info"></p>
            </div>
            <div class="bth-preview-panel">
                <h4><?php esc_html_e( 'Converted', 'ald-business-tools' ); ?></h4>
                <canvas id="bth-convert-output-canvas"></canvas>
                <p class="bth-preview-size" id="bth-convert-output-info"></p>
            </div>
        </div>

        <div class="bth-image-stats" id="bth-convert-stats">
            <div class="bth-stat">
                <span class="bth-stat-label"><?php esc_html_e( 'Original Format:', 'ald-business-tools' ); ?></span>
                <span class="bth-stat-value" id="bth-convert-stat-original-format"></span>
            </div>
            <div class="bth-stat">
                <span class="bth-stat-label"><?php esc_html_e( 'Converted Format:', 'ald-business-tools' ); ?></span>
                <span class="bth-stat-value" id="bth-convert-stat-output-format"></span>
            </div>
            <div class="bth-stat">
                <span class="bth-stat-label"><?php esc_html_e( 'Original Size:', 'ald-business-tools' ); ?></span>
                <span class="bth-stat-value" id="bth-convert-stat-original-size"></span>
            </div>
            <div class="bth-stat">
                <span class="bth-stat-label"><?php esc_html_e( 'Converted Size:', 'ald-business-tools' ); ?></span>
                <span class="bth-stat-value" id="bth-convert-stat-output-size"></span>
            </div>
        </div>

        <div class="bth-form-group bth-form-actions">
            <button type="button" class="bth-btn bth-btn-secondary" id="bth-convert-download"><?php esc_html_e( 'Download Converted Image', 'ald-business-tools' ); ?></button>
        </div>
    </div>
</div>

<script>
(function () {
    'use strict';

    var dropzone   = document.getElementById('bth-convert-dropzone');
    var fileInput  = document.getElementById('bth-convert-input');
    var formatSel  = document.getElementById('bth-convert-format');
    var qualitySlider = document.getElementById('bth-convert-quality');
    var qualityValue  = document.getElementById('bth-convert-quality-value');
    var convertBtn = document.getElementById('bth-convert-btn');
    var resetBtn   = document.getElementById('bth-convert-reset');
    var resultBox  = document.getElementById('bth-convert-result');
    var originalCanvas = document.getElementById('bth-convert-original-canvas');
    var outputCanvas   = document.getElementById('bth-convert-output-canvas');
    var originalInfo   = document.getElementById('bth-convert-original-info');
    var outputInfo     = document.getElementById('bth-convert-output-info');
    var downloadBtn    = document.getElementById('bth-convert-download');

    var statOrigFormat  = document.getElementById('bth-convert-stat-original-format');
    var statOutFormat   = document.getElementById('bth-convert-stat-output-format');
    var statOrigSize    = document.getElementById('bth-convert-stat-original-size');
    var statOutSize     = document.getElementById('bth-convert-stat-output-size');

    var currentFile = null;
    var originalFileSize = 0;
    var convertedDataUrl = null;
    var convertedMime = '';

    // Quality slider
    qualitySlider.addEventListener('input', function () {
        qualityValue.textContent = qualitySlider.value;
    });

    // Dropzone
    dropzone.addEventListener('click', function (e) {
        if (e.target !== fileInput) fileInput.click();
    });
    dropzone.addEventListener('dragover', function (e) {
        e.preventDefault();
        dropzone.classList.add('bth-dropzone-dragover');
    });
    dropzone.addEventListener('dragleave', function (e) {
        e.preventDefault();
        dropzone.classList.remove('bth-dropzone-dragover');
    });
    dropzone.addEventListener('drop', function (e) {
        e.preventDefault();
        dropzone.classList.remove('bth-dropzone-dragover');
        if (e.dataTransfer.files.length > 0) handleFile(e.dataTransfer.files[0]);
    });
    fileInput.addEventListener('change', function () {
        if (fileInput.files.length > 0) handleFile(fileInput.files[0]);
    });

    function handleFile(file) {
        if (!file.type.match(/^image\//)) {
            alert('<?php echo esc_js( __( 'Please select a valid image file.', 'ald-business-tools' ) ); ?>');
            return;
        }
        currentFile = file;
        originalFileSize = file.size;
        convertBtn.disabled = false;
        resultBox.style.display = 'none';
        convertedDataUrl = null;

        // Auto-select a different default format
        var currentMime = file.type;
        var options = formatSel.options;
        for (var i = 0; i < options.length; i++) {
            if (options[i].value !== currentMime) {
                formatSel.selectedIndex = i;
                break;
            }
        }

        // Show original preview
        var reader = new FileReader();
        reader.onload = function (e) {
            var img = new Image();
            img.onload = function () {
                drawToCanvas(originalCanvas, img);
                originalInfo.textContent = formatBytes(originalFileSize) + ' (' + img.width + 'x' + img.height + ')';
            };
            img.src = e.target.result;
        };
        reader.readAsDataURL(file);
    }

    // Convert
    convertBtn.addEventListener('click', function () {
        if (!currentFile) return;

        var quality = parseInt(qualitySlider.value, 10) / 100;
        var outputType = formatSel.value;
        convertedMime = outputType;

        var reader = new FileReader();
        reader.onload = function (e) {
            var img = new Image();
            img.onload = function () {
                var canvas = document.createElement('canvas');
                canvas.width = img.width;
                canvas.height = img.height;
                var ctx = canvas.getContext('2d');

                // White background for JPEG
                if (outputType === 'image/jpeg') {
                    ctx.fillStyle = '#FFFFFF';
                    ctx.fillRect(0, 0, canvas.width, canvas.height);
                }
                ctx.drawImage(img, 0, 0);

                convertedDataUrl = canvas.toDataURL(outputType, quality);

                // Calculate size
                var base64 = convertedDataUrl.split(',')[1] || '';
                var convertedBytes = Math.round((base64.length * 3) / 4);

                // Draw output preview
                var outImg = new Image();
                outImg.onload = function () {
                    drawToCanvas(outputCanvas, outImg);
                    outputInfo.textContent = formatBytes(convertedBytes) + ' (' + img.width + 'x' + img.height + ')';
                };
                outImg.src = convertedDataUrl;

                // Stats
                statOrigFormat.textContent = currentFile.type.replace('image/', '').toUpperCase();
                statOutFormat.textContent = outputType.replace('image/', '').toUpperCase();
                statOrigSize.textContent = formatBytes(originalFileSize);
                statOutSize.textContent = formatBytes(convertedBytes);

                resultBox.style.display = 'block';
            };
            img.src = e.target.result;
        };
        reader.readAsDataURL(currentFile);
    });

    // Download
    downloadBtn.addEventListener('click', function () {
        if (!convertedDataUrl) return;
        var link = document.createElement('a');
        link.download = 'converted-image';
        var ext = convertedMime.replace('image/', '');
        if (ext === 'jpeg') ext = 'jpg';
        link.download += '.' + ext;
        link.href = convertedDataUrl;
        document.body.appendChild(link);
        link.click();
        document.body.removeChild(link);
    });

    // Reset
    resetBtn.addEventListener('click', function () {
        currentFile = null;
        originalFileSize = 0;
        convertedDataUrl = null;
        fileInput.value = '';
        qualitySlider.value = 80;
        qualityValue.textContent = '80';
        convertBtn.disabled = true;
        resultBox.style.display = 'none';
        originalInfo.textContent = '';
        outputInfo.textContent = '';
        statOrigFormat.textContent = '';
        statOutFormat.textContent = '';
        statOrigSize.textContent = '';
        statOutSize.textContent = '';
        var oc = originalCanvas.getContext('2d');
        oc.clearRect(0, 0, originalCanvas.width, originalCanvas.height);
        var oc2 = outputCanvas.getContext('2d');
        oc2.clearRect(0, 0, outputCanvas.width, outputCanvas.height);
    });

    function drawToCanvas(canvas, img) {
        canvas.width = img.width;
        canvas.height = img.height;
        var ctx = canvas.getContext('2d');
        ctx.clearRect(0, 0, canvas.width, canvas.height);
        ctx.drawImage(img, 0, 0);
    }

    function formatBytes(bytes) {
        if (bytes === 0) return '0 B';
        var k = 1024;
        var sizes = ['B', 'KB', 'MB', 'GB'];
        var i = Math.floor(Math.log(bytes) / Math.log(k));
        return parseFloat((bytes / Math.pow(k, i)).toFixed(2)) + ' ' + sizes[i];
    }
})();
</script>
