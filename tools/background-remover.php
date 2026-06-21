<?php
/**
 * Background Remover Tool
 * Remove solid-color backgrounds using color tolerance
 *
 * @package ALD_Business_Tools_Hub
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}
?>
<div class="bth-image-wrap">
    <div class="bth-dropzone" id="bth-bg-dropzone">
        <div class="bth-dropzone-icon">
            <svg xmlns="http://www.w3.org/2000/svg" width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><polyline points="17 8 12 3 7 8"/><line x1="12" y1="3" x2="12" y2="15"/></svg>
        </div>
        <p class="bth-dropzone-text"><?php esc_html_e( 'Drag & drop an image here, or click to browse', 'ald-business-tools' ); ?></p>
        <p class="bth-dropzone-hint"><?php esc_html_e( 'Supports JPG, PNG, WebP — best with solid-color backgrounds', 'ald-business-tools' ); ?></p>
        <input type="file" id="bth-bg-input" accept="image/*" class="bth-dropzone-input">
    </div>

    <div class="bth-bg-controls" id="bth-bg-controls" style="display:none;">
        <div class="bth-form-group">
            <label class="bth-form-label"><?php esc_html_e( 'Background Color', 'ald-business-tools' ); ?></label>
            <div class="bth-color-picker-wrap">
                <input class="bth-color-input" type="color" id="bth-bg-color" value="#FFFFFF">
                <input class="bth-form-input bth-color-hex-input" type="text" id="bth-bg-color-hex" value="#FFFFFF" maxlength="7" placeholder="#FFFFFF">
            </div>
            <p class="bth-bg-hint"><?php esc_html_e( 'Click the image below to pick the background color, or use the color picker.', 'ald-business-tools' ); ?></p>
        </div>

        <div class="bth-form-group">
            <label class="bth-form-label" for="bth-bg-tolerance"><?php esc_html_e( 'Tolerance', 'ald-business-tools' ); ?>: <span id="bth-bg-tolerance-value">30</span>%</label>
            <input class="bth-form-input" type="range" id="bth-bg-tolerance" min="0" max="100" value="30">
        </div>

        <div class="bth-form-group bth-form-actions">
            <button type="button" class="bth-btn bth-btn-primary" id="bth-bg-remove-btn"><?php esc_html_e( 'Remove Background', 'ald-business-tools' ); ?></button>
            <button type="button" class="bth-btn bth-btn-secondary" id="bth-bg-reset"><?php esc_html_e( 'Reset', 'ald-business-tools' ); ?></button>
        </div>
    </div>

    <div class="bth-result-box" id="bth-bg-result" style="display:none;">
        <div class="bth-image-preview">
            <div class="bth-preview-panel">
                <h4><?php esc_html_e( 'Original', 'ald-business-tools' ); ?></h4>
                <canvas id="bth-bg-original-canvas"></canvas>
                <p class="bth-preview-size" id="bth-bg-original-info"></p>
            </div>
            <div class="bth-preview-panel">
                <h4><?php esc_html_e( 'Background Removed', 'ald-business-tools' ); ?></h4>
                <canvas id="bth-bg-output-canvas"></canvas>
                <p class="bth-preview-size" id="bth-bg-output-info"></p>
            </div>
        </div>

        <div class="bth-form-group bth-form-actions">
            <button type="button" class="bth-btn bth-btn-secondary" id="bth-bg-download"><?php esc_html_e( 'Download as PNG', 'ald-business-tools' ); ?></button>
        </div>
    </div>
</div>

<script>
(function () {
    'use strict';

    var dropzone   = document.getElementById('bth-bg-dropzone');
    var fileInput  = document.getElementById('bth-bg-input');
    var controls   = document.getElementById('bth-bg-controls');
    var colorInput = document.getElementById('bth-bg-color');
    var colorHex   = document.getElementById('bth-bg-color-hex');
    var toleranceSlider = document.getElementById('bth-bg-tolerance');
    var toleranceValue  = document.getElementById('bth-bg-tolerance-value');
    var removeBtn  = document.getElementById('bth-bg-remove-btn');
    var resetBtn   = document.getElementById('bth-bg-reset');
    var resultBox  = document.getElementById('bth-bg-result');
    var originalCanvas = document.getElementById('bth-bg-original-canvas');
    var outputCanvas   = document.getElementById('bth-bg-output-canvas');
    var originalInfo   = document.getElementById('bth-bg-original-info');
    var outputInfo     = document.getElementById('bth-bg-output-info');
    var downloadBtn    = document.getElementById('bth-bg-download');

    var currentFile = null;
    var originalFileSize = 0;
    var outputDataUrl = null;

    // Tolerance slider
    toleranceSlider.addEventListener('input', function () {
        toleranceValue.textContent = toleranceSlider.value;
    });

    // Color picker sync
    colorInput.addEventListener('input', function () {
        colorHex.value = colorInput.value.toUpperCase();
    });
    colorHex.addEventListener('input', function () {
        var hex = colorHex.value.trim();
        if (/^#[0-9A-Fa-f]{6}$/.test(hex)) {
            colorInput.value = hex;
        }
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
        controls.style.display = 'block';
        resultBox.style.display = 'none';
        outputDataUrl = null;

        // Detect corner color as initial background guess
        var reader = new FileReader();
        reader.onload = function (e) {
            var img = new Image();
            img.onload = function () {
                drawToCanvas(originalCanvas, img);
                originalInfo.textContent = formatBytes(originalFileSize) + ' (' + img.width + 'x' + img.height + ')';

                // Sample top-left pixel for initial bg color
                var ctx = originalCanvas.getContext('2d');
                var pixel = ctx.getImageData(0, 0, 1, 1).data;
                var hex = '#' + [pixel[0], pixel[1], pixel[2]].map(function (v) {
                    return v.toString(16).padStart(2, '0');
                }).join('');
                colorInput.value = hex;
                colorHex.value = hex.toUpperCase();
            };
            img.src = e.target.result;
        };
        reader.readAsDataURL(file);
    }

    // Click on original canvas to pick color
    originalCanvas.addEventListener('click', function (e) {
        var rect = originalCanvas.getBoundingClientRect();
        var scaleX = originalCanvas.width / rect.width;
        var scaleY = originalCanvas.height / rect.height;
        var x = Math.floor((e.clientX - rect.left) * scaleX);
        var y = Math.floor((e.clientY - rect.top) * scaleY);
        var ctx = originalCanvas.getContext('2d');
        var pixel = ctx.getImageData(x, y, 1, 1).data;
        var hex = '#' + [pixel[0], pixel[1], pixel[2]].map(function (v) {
            return v.toString(16).padStart(2, '0');
        }).join('');
        colorInput.value = hex;
        colorHex.value = hex.toUpperCase();
    });
    originalCanvas.style.cursor = 'crosshair';

    // Remove background
    removeBtn.addEventListener('click', function () {
        if (!currentFile) return;

        var tolerance = parseInt(toleranceSlider.value, 10);
        var bgHex = colorInput.value;
        var bgR = parseInt(bgHex.substr(1, 2), 16);
        var bgG = parseInt(bgHex.substr(3, 2), 16);
        var bgB = parseInt(bgHex.substr(5, 2), 16);

        var reader = new FileReader();
        reader.onload = function (e) {
            var img = new Image();
            img.onload = function () {
                var canvas = document.createElement('canvas');
                canvas.width = img.width;
                canvas.height = img.height;
                var ctx = canvas.getContext('2d');
                ctx.drawImage(img, 0, 0);

                var imageData = ctx.getImageData(0, 0, canvas.width, canvas.height);
                var data = imageData.data;
                var tol = tolerance * 2.55; // Convert % to 0-255 range

                for (var i = 0; i < data.length; i += 4) {
                    var dr = Math.abs(data[i] - bgR);
                    var dg = Math.abs(data[i + 1] - bgG);
                    var db = Math.abs(data[i + 2] - bgB);
                    if (dr <= tol && dg <= tol && db <= tol) {
                        data[i + 3] = 0; // Set alpha to 0
                    }
                }

                ctx.putImageData(imageData, 0, 0);
                outputDataUrl = canvas.toDataURL('image/png');

                // Calculate size
                var base64 = outputDataUrl.split(',')[1] || '';
                var outputBytes = Math.round((base64.length * 3) / 4);

                // Draw output preview
                var outImg = new Image();
                outImg.onload = function () {
                    drawToCanvas(outputCanvas, outImg);
                    outputInfo.textContent = formatBytes(outputBytes) + ' (' + img.width + 'x' + img.height + ')';
                };
                outImg.src = outputDataUrl;

                resultBox.style.display = 'block';
            };
            img.src = e.target.result;
        };
        reader.readAsDataURL(currentFile);
    });

    // Download
    downloadBtn.addEventListener('click', function () {
        if (!outputDataUrl) return;
        var link = document.createElement('a');
        link.download = 'background-removed.png';
        link.href = outputDataUrl;
        document.body.appendChild(link);
        link.click();
        document.body.removeChild(link);
    });

    // Reset
    resetBtn.addEventListener('click', function () {
        currentFile = null;
        originalFileSize = 0;
        outputDataUrl = null;
        fileInput.value = '';
        toleranceSlider.value = 30;
        toleranceValue.textContent = '30';
        controls.style.display = 'none';
        resultBox.style.display = 'none';
        originalInfo.textContent = '';
        outputInfo.textContent = '';
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
