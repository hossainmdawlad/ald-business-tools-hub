<?php
/**
 * Social Media Image Resizer Tool
 */
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

$presets = array(
    'facebook-post'    => array( 'label' => __( 'Facebook Post', 'ald-business-tools' ),    'w' => 1200, 'h' => 630 ),
    'facebook-cover'   => array( 'label' => __( 'Facebook Cover', 'ald-business-tools' ),   'w' => 820,  'h' => 312 ),
    'instagram-post'   => array( 'label' => __( 'Instagram Post', 'ald-business-tools' ),   'w' => 1080, 'h' => 1080 ),
    'instagram-story'  => array( 'label' => __( 'Instagram Story', 'ald-business-tools' ),  'w' => 1080, 'h' => 1920 ),
    'twitter-post'     => array( 'label' => __( 'Twitter / X Post', 'ald-business-tools' ), 'w' => 1200, 'h' => 675 ),
    'twitter-header'   => array( 'label' => __( 'Twitter / X Header', 'ald-business-tools' ), 'w' => 1500, 'h' => 500 ),
    'linkedin-post'    => array( 'label' => __( 'LinkedIn Post', 'ald-business-tools' ),    'w' => 1200, 'h' => 627 ),
    'youtube-thumb'    => array( 'label' => __( 'YouTube Thumbnail', 'ald-business-tools' ), 'w' => 1280, 'h' => 720 ),
    'pinterest-pin'    => array( 'label' => __( 'Pinterest Pin', 'ald-business-tools' ),    'w' => 1000, 'h' => 1500 ),
    'custom'           => array( 'label' => __( 'Custom', 'ald-business-tools' ),           'w' => 0,    'h' => 0 ),
);
?>
<div class="bth-resize-wrap">
    <form id="bth-resize-form" class="bth-form">
        <div class="bth-form-group">
            <label class="bth-form-label"><?php esc_html_e( 'Upload Image', 'ald-business-tools' ); ?></label>
            <div id="bth-resize-dropzone" class="bth-dropzone">
                <span class="bth-dropzone-text"><?php esc_html_e( 'Drag & drop an image here or click to browse', 'ald-business-tools' ); ?></span>
                <input type="file" id="bth-resize-file" accept="image/*" style="display:none;">
            </div>
            <p id="bth-resize-fileinfo" style="display:none;font-size:13px;color:var(--color-text-muted);margin-top:4px;"></p>
        </div>
        <div class="bth-form-group">
            <label for="bth-resize-preset" class="bth-form-label"><?php esc_html_e( 'Platform Preset', 'ald-business-tools' ); ?></label>
            <select id="bth-resize-preset" class="bth-form-select">
                <?php foreach ( $presets as $key => $p ) : ?>
                    <option value="<?php echo esc_attr( $key ); ?>" data-w="<?php echo esc_attr( $p['w'] ); ?>" data-h="<?php echo esc_attr( $p['h'] ); ?>">
                        <?php echo esc_html( $p['label'] ); ?> <?php if ( $p['w'] ) echo '(' . esc_html( $p['w'] . 'x' . $p['h'] ) . ')'; ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>
        <div id="bth-resize-custom-size" class="bth-form-group bth-resize-custom-size" style="display:none;">
            <div style="display:flex;gap:12px;">
                <div style="flex:1;">
                    <label for="bth-resize-custom-w" class="bth-form-label"><?php esc_html_e( 'Width (px)', 'ald-business-tools' ); ?></label>
                    <input type="number" id="bth-resize-custom-w" class="bth-form-input" min="1" max="5000" value="1080">
                </div>
                <div style="flex:1;">
                    <label for="bth-resize-custom-h" class="bth-form-label"><?php esc_html_e( 'Height (px)', 'ald-business-tools' ); ?></label>
                    <input type="number" id="bth-resize-custom-h" class="bth-form-input" min="1" max="5000" value="1080">
                </div>
            </div>
        </div>
        <div class="bth-form-group">
            <label style="display:flex;align-items:center;gap:8px;cursor:pointer;">
                <input type="checkbox" id="bth-resize-keep-ratio" checked>
                <span><?php esc_html_e( 'Maintain aspect ratio', 'ald-business-tools' ); ?></span>
            </label>
        </div>
        <div class="bth-form-actions">
            <button type="submit" class="bth-btn bth-btn-primary" id="bth-resize-btn" disabled><?php esc_html_e( 'Resize', 'ald-business-tools' ); ?></button>
            <button type="button" id="bth-resize-reset" class="bth-btn bth-btn-secondary"><?php esc_html_e( 'Reset', 'ald-business-tools' ); ?></button>
        </div>
    </form>
    <div id="bth-resize-result" class="bth-result-box" style="display:none;">
        <div class="bth-resize-preview">
            <div class="bth-resize-preview-item">
                <strong><?php esc_html_e( 'Original', 'ald-business-tools' ); ?></strong>
                <canvas id="bth-resize-canvas-original"></canvas>
                <span id="bth-resize-original-dims"></span>
            </div>
            <div class="bth-resize-preview-item">
                <strong><?php esc_html_e( 'Resized', 'ald-business-tools' ); ?></strong>
                <canvas id="bth-resize-canvas-resized"></canvas>
                <span id="bth-resize-resized-dims"></span>
            </div>
        </div>
        <button type="button" id="bth-resize-download" class="bth-btn bth-btn-primary" style="margin-top:12px;"><?php esc_html_e( 'Download Resized Image', 'ald-business-tools' ); ?></button>
    </div>
</div>
<script>
(function(){
    var fileInput = document.getElementById('bth-resize-file');
    var dropzone = document.getElementById('bth-resize-dropzone');
    var fileInfo = document.getElementById('bth-resize-fileinfo');
    var presetSelect = document.getElementById('bth-resize-preset');
    var customSize = document.getElementById('bth-resize-custom-size');
    var resizeBtn = document.getElementById('bth-resize-btn');
    var result = document.getElementById('bth-resize-result');
    var keepRatio = document.getElementById('bth-resize-keep-ratio');
    var form = document.getElementById('bth-resize-form');
    var originalCanvas = document.getElementById('bth-resize-canvas-original');
    var resizedCanvas = document.getElementById('bth-resize-canvas-resized');
    var originalDims = document.getElementById('bth-resize-original-dims');
    var resizedDims = document.getElementById('bth-resize-resized-dims');

    var currentImage = null;
    var originalFileName = 'resized';

    // Dropzone
    dropzone.addEventListener('click', function(){ fileInput.click(); });
    dropzone.addEventListener('dragover', function(e){ e.preventDefault(); dropzone.style.borderColor = 'var(--color-primary)'; });
    dropzone.addEventListener('dragleave', function(){ dropzone.style.borderColor = ''; });
    dropzone.addEventListener('drop', function(e){
        e.preventDefault();
        dropzone.style.borderColor = '';
        if (e.dataTransfer.files.length) handleFile(e.dataTransfer.files[0]);
    });
    fileInput.addEventListener('change', function(){
        if (fileInput.files.length) handleFile(fileInput.files[0]);
    });

    function handleFile(file) {
        if (!file.type.startsWith('image/')) return;
        originalFileName = file.name.replace(/\.[^.]+$/, '');
        var reader = new FileReader();
        reader.onload = function(e){
            var img = new Image();
            img.onload = function(){
                currentImage = img;
                resizeBtn.disabled = false;
                dropzone.querySelector('.bth-dropzone-text').textContent = file.name;
                fileInfo.style.display = '';
                fileInfo.textContent = Math.round(file.size/1024) + ' KB • ' + img.width + 'x' + img.height;
            };
            img.src = e.target.result;
        };
        reader.readAsDataURL(file);
    }

    // Preset change
    presetSelect.addEventListener('change', function(){
        var opt = this.options[this.selectedIndex];
        if (opt.value === 'custom') {
            customSize.style.display = '';
        } else {
            customSize.style.display = 'none';
        }
    });

    // Resize
    form.addEventListener('submit', function(e){
        e.preventDefault();
        if (!currentImage) return;

        var w, h;
        var opt = presetSelect.options[presetSelect.selectedIndex];
        if (opt.value === 'custom') {
            w = parseInt(document.getElementById('bth-resize-custom-w').value) || currentImage.width;
            h = parseInt(document.getElementById('bth-resize-custom-h').value) || currentImage.height;
        } else {
            w = parseInt(opt.dataset.w);
            h = parseInt(opt.dataset.h);
        }

        if (keepRatio.checked) {
            var ratio = currentImage.width / currentImage.height;
            if (w / h > ratio) {
                w = Math.round(h * ratio);
            } else {
                h = Math.round(w / ratio);
            }
        }

        // Draw original
        var origCtx = originalCanvas.getContext('2d');
        originalCanvas.width = currentImage.width;
        originalCanvas.height = currentImage.height;
        origCtx.drawImage(currentImage, 0, 0);
        originalDims.textContent = currentImage.width + 'x' + currentImage.height + ' px';

        // Draw resized
        var resCtx = resizedCanvas.getContext('2d');
        resizedCanvas.width = w;
        resizedCanvas.height = h;
        resCtx.drawImage(currentImage, 0, 0, w, h);
        resizedDims.textContent = w + 'x' + h + ' px';

        result.style.display = 'block';
    });

    // Download
    document.getElementById('bth-resize-download').addEventListener('click', function(){
        var link = document.createElement('a');
        link.download = originalFileName + '-' + resizedCanvas.width + 'x' + resizedCanvas.height + '.png';
        link.href = resizedCanvas.toDataURL('image/png');
        link.click();
    });

    // Reset
    document.getElementById('bth-resize-reset').addEventListener('click', function(){
        form.reset();
        currentImage = null;
        resizeBtn.disabled = true;
        result.style.display = 'none';
        customSize.style.display = 'none';
        fileInfo.style.display = 'none';
        dropzone.querySelector('.bth-dropzone-text').textContent = '<?php echo esc_js( __( 'Drag & drop an image here or click to browse', 'ald-business-tools' ) ); ?>';
    });
})();
</script>
