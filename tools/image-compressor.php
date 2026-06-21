<?php
/**
 * Image Compressor Tool
 *
 * @package ALD_Business_Tools_Hub
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}
?>
<div class="bth-image-wrap">
    <div class="bth-dropzone" id="bth-image-dropzone">
        <div class="bth-dropzone-icon">
            <svg xmlns="http://www.w3.org/2000/svg" width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><polyline points="17 8 12 3 7 8"/><line x1="12" y1="3" x2="12" y2="15"/></svg>
        </div>
        <p class="bth-dropzone-text"><?php esc_html_e( 'Drag & drop an image here, or click to browse', 'ald-business-tools' ); ?></p>
        <p class="bth-dropzone-hint"><?php esc_html_e( 'Supports JPG, PNG, WebP, GIF, BMP', 'ald-business-tools' ); ?></p>
        <input type="file" id="bth-image-input" accept="image/*" class="bth-dropzone-input">
    </div>

    <div class="bth-form-group">
        <label class="bth-form-label" for="bth-image-quality"><?php esc_html_e( 'Quality', 'ald-business-tools' ); ?>: <span id="bth-quality-value">70</span>%</label>
        <input class="bth-form-input" type="range" id="bth-image-quality" min="10" max="100" value="70">
    </div>

    <div class="bth-form-group bth-form-row">
        <div class="bth-form-col">
            <label class="bth-form-label" for="bth-image-maxwidth"><?php esc_html_e( 'Max Width (px)', 'ald-business-tools' ); ?></label>
            <input class="bth-form-input" type="number" id="bth-image-maxwidth" min="1" max="10000" placeholder="<?php esc_attr_e( 'Optional', 'ald-business-tools' ); ?>">
        </div>
        <div class="bth-form-col">
            <label class="bth-form-label" for="bth-image-maxheight"><?php esc_html_e( 'Max Height (px)', 'ald-business-tools' ); ?></label>
            <input class="bth-form-input" type="number" id="bth-image-maxheight" min="1" max="10000" placeholder="<?php esc_attr_e( 'Optional', 'ald-business-tools' ); ?>">
        </div>
    </div>

    <div class="bth-form-group bth-form-actions">
        <button type="button" class="bth-btn bth-btn-primary" id="bth-image-compress" disabled><?php esc_html_e( 'Compress Image', 'ald-business-tools' ); ?></button>
        <button type="button" class="bth-btn bth-btn-secondary" id="bth-image-reset"><?php esc_html_e( 'Reset', 'ald-business-tools' ); ?></button>
    </div>

    <div class="bth-result-box" id="bth-image-result" style="display:none;">
        <div class="bth-image-preview">
            <div class="bth-preview-panel">
                <h4><?php esc_html_e( 'Original', 'ald-business-tools' ); ?></h4>
                <canvas id="bth-image-original-canvas"></canvas>
                <p class="bth-preview-size" id="bth-original-size"></p>
            </div>
            <div class="bth-preview-panel">
                <h4><?php esc_html_e( 'Compressed', 'ald-business-tools' ); ?></h4>
                <canvas id="bth-image-compressed-canvas"></canvas>
                <p class="bth-preview-size" id="bth-compressed-size"></p>
            </div>
        </div>

        <div class="bth-image-stats" id="bth-image-stats">
            <div class="bth-stat">
                <span class="bth-stat-label"><?php esc_html_e( 'Original Size:', 'ald-business-tools' ); ?></span>
                <span class="bth-stat-value" id="bth-stat-original"></span>
            </div>
            <div class="bth-stat">
                <span class="bth-stat-label"><?php esc_html_e( 'Compressed Size:', 'ald-business-tools' ); ?></span>
                <span class="bth-stat-value" id="bth-stat-compressed"></span>
            </div>
            <div class="bth-stat">
                <span class="bth-stat-label"><?php esc_html_e( 'Savings:', 'ald-business-tools' ); ?></span>
                <span class="bth-stat-value" id="bth-stat-savings"></span>
            </div>
        </div>

        <div class="bth-form-group bth-form-actions">
            <button type="button" class="bth-btn bth-btn-secondary" id="bth-image-download"><?php esc_html_e( 'Download Compressed Image', 'ald-business-tools' ); ?></button>
        </div>
    </div>
</div>

<script>
(function () {
    'use strict';

    var dropzone   = document.getElementById('bth-image-dropzone');
    var fileInput  = document.getElementById('bth-image-input');
    var qualitySlider = document.getElementById('bth-image-quality');
    var qualityValue  = document.getElementById('bth-quality-value');
    var maxWidthInput  = document.getElementById('bth-image-maxwidth');
    var maxHeightInput = document.getElementById('bth-image-maxheight');
    var compressBtn = document.getElementById('bth-image-compress');
    var resetBtn    = document.getElementById('bth-image-reset');
    var resultBox   = document.getElementById('bth-image-result');
    var originalCanvas   = document.getElementById('bth-image-original-canvas');
    var compressedCanvas = document.getElementById('bth-image-compressed-canvas');
    var originalSizeLabel   = document.getElementById('bth-original-size');
    var compressedSizeLabel = document.getElementById('bth-compressed-size');
    var statOriginal   = document.getElementById('bth-stat-original');
    var statCompressed = document.getElementById('bth-stat-compressed');
    var statSavings    = document.getElementById('bth-stat-savings');
    var downloadBtn    = document.getElementById('bth-image-download');

    var currentFile = null;
    var originalFileSize = 0;
    var compressedDataUrl = null;

    // ---- Quality slider label ----
    qualitySlider.addEventListener('input', function () {
        qualityValue.textContent = qualitySlider.value;
    });

    // ---- Drag & drop ----
    dropzone.addEventListener('click', function (e) {
        if (e.target !== fileInput) {
            fileInput.click();
        }
    });

    dropzone.addEventListener('dragover', function (e) {
        e.preventDefault();
        e.stopPropagation();
        dropzone.classList.add('bth-dropzone-dragover');
    });

    dropzone.addEventListener('dragleave', function (e) {
        e.preventDefault();
        e.stopPropagation();
        dropzone.classList.remove('bth-dropzone-dragover');
    });

    dropzone.addEventListener('drop', function (e) {
        e.preventDefault();
        e.stopPropagation();
        dropzone.classList.remove('bth-dropzone-dragover');
        var files = e.dataTransfer.files;
        if (files.length > 0) {
            handleFile(files[0]);
        }
    });

    fileInput.addEventListener('change', function () {
        if (fileInput.files.length > 0) {
            handleFile(fileInput.files[0]);
        }
    });

    // ---- Handle file ----
    function handleFile(file) {
        if (!file.type.match(/^image\//)) {
            alert('<?php echo esc_js( __( 'Please select a valid image file.', 'ald-business-tools' ) ); ?>');
            return;
        }
        currentFile = file;
        originalFileSize = file.size;
        compressBtn.disabled = false;
        resultBox.style.display = 'none';
        compressedDataUrl = null;

        // Show original preview
        var reader = new FileReader();
        reader.onload = function (e) {
            var img = new Image();
            img.onload = function () {
                drawToCanvas(originalCanvas, img);
                originalSizeLabel.textContent = formatBytes(originalFileSize) + ' (' + img.width + 'x' + img.height + ')';
            };
            img.src = e.target.result;
        };
        reader.readAsDataURL(file);
    }

    // ---- Compress ----
    compressBtn.addEventListener('click', function () {
        if (!currentFile) return;

        var quality = parseInt(qualitySlider.value, 10) / 100;
        var maxWidth = parseInt(maxWidthInput.value, 10) || 0;
        var maxHeight = parseInt(maxHeightInput.value, 10) || 0;

        var reader = new FileReader();
        reader.onload = function (e) {
            var img = new Image();
            img.onload = function () {
                var w = img.width;
                var h = img.height;

                // Scale down if max dimensions set
                if (maxWidth > 0 && w > maxWidth) {
                    h = Math.round(h * (maxWidth / w));
                    w = maxWidth;
                }
                if (maxHeight > 0 && h > maxHeight) {
                    w = Math.round(w * (maxHeight / h));
                    h = maxHeight;
                }

                // Always use JPEG for compression — quality slider actually works
                var outputType = 'image/jpeg';
                var outputQuality = quality;

                // If original is PNG with transparency and no resize/quality=100 requested, keep PNG
                // But for compression purposes, JPEG gives real file size reduction
                var canvas = document.createElement('canvas');
                canvas.width = w;
                canvas.height = h;
                var ctx = canvas.getContext('2d');

                // White background for JPEG (transparent areas become white)
                ctx.fillStyle = '#FFFFFF';
                ctx.fillRect(0, 0, w, h);
                ctx.drawImage(img, 0, 0, w, h);

                compressedDataUrl = canvas.toDataURL(outputType, outputQuality);

                // Calculate real byte size from base64 data URL
                var base64 = compressedDataUrl.split(',')[1] || '';
                var compressedBytes = Math.round((base64.length * 3) / 4);

                // Draw compressed preview
                var compImg = new Image();
                compImg.onload = function () {
                    drawToCanvas(compressedCanvas, compImg);
                    compressedSizeLabel.textContent = formatBytes(compressedBytes) + ' (' + w + 'x' + h + ')';
                };
                compImg.src = compressedDataUrl;

                // Stats
                statOriginal.textContent = formatBytes(originalFileSize);
                statCompressed.textContent = formatBytes(compressedBytes);
                var savings = originalFileSize > 0
                    ? Math.round((1 - compressedBytes / originalFileSize) * 100)
                    : 0;
                statSavings.textContent = savings + '%';

                resultBox.style.display = 'block';
            };
            img.src = e.target.result;
        };
        reader.readAsDataURL(currentFile);
    });

    // ---- Download ----
    downloadBtn.addEventListener('click', function () {
        if (!compressedDataUrl) return;
        var link = document.createElement('a');
        link.download = 'compressed-image';
        // Pick extension from mime type
        if (compressedDataUrl.indexOf('image/png') !== -1) link.download += '.png';
        else if (compressedDataUrl.indexOf('image/webp') !== -1) link.download += '.webp';
        else link.download += '.jpg';
        link.href = compressedDataUrl;
        document.body.appendChild(link);
        link.click();
        document.body.removeChild(link);
    });

    // ---- Reset ----
    resetBtn.addEventListener('click', function () {
        currentFile = null;
        originalFileSize = 0;
        compressedDataUrl = null;
        fileInput.value = '';
        qualitySlider.value = 70;
        qualityValue.textContent = '70';
        maxWidthInput.value = '';
        maxHeightInput.value = '';
        compressBtn.disabled = true;
        resultBox.style.display = 'none';

        var origCtx = originalCanvas.getContext('2d');
        origCtx.clearRect(0, 0, originalCanvas.width, originalCanvas.height);
        var compCtx = compressedCanvas.getContext('2d');
        compCtx.clearRect(0, 0, compressedCanvas.width, compressedCanvas.height);

        originalSizeLabel.textContent = '';
        compressedSizeLabel.textContent = '';
        statOriginal.textContent = '';
        statCompressed.textContent = '';
        statSavings.textContent = '';
    });

    // ---- Helpers ----
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
