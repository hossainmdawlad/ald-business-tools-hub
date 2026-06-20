<?php
/**
 * QR Code Generator Tool
 *
 * @package ALD_Business_Tools_Hub
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}
?>
<div class="bth-qr-wrap">
    <div class="bth-form-group">
        <label class="bth-form-label" for="bth-qr-input"><?php esc_html_e( 'Text or URL', 'ald-business-tools' ); ?></label>
        <textarea class="bth-form-textarea" id="bth-qr-input" rows="4" placeholder="<?php esc_attr_e( 'Enter text or URL to encode…', 'ald-business-tools' ); ?>"></textarea>
    </div>

    <div class="bth-form-group">
        <label class="bth-form-label" for="bth-qr-size"><?php esc_html_e( 'Size', 'ald-business-tools' ); ?></label>
        <select class="bth-form-select" id="bth-qr-size">
            <option value="128"><?php esc_html_e( 'Small (128px)', 'ald-business-tools' ); ?></option>
            <option value="256" selected><?php esc_html_e( 'Medium (256px)', 'ald-business-tools' ); ?></option>
            <option value="512"><?php esc_html_e( 'Large (512px)', 'ald-business-tools' ); ?></option>
        </select>
    </div>

    <div class="bth-form-group">
        <button type="button" class="bth-btn bth-btn-primary" id="bth-qr-generate"><?php esc_html_e( 'Generate QR Code', 'ald-business-tools' ); ?></button>
    </div>

    <div class="bth-qr-result" id="bth-qr-result">
        <canvas id="bth-qr-canvas"></canvas>
    </div>

    <div class="bth-form-group" id="bth-qr-download-wrap" style="display:none;">
        <button type="button" class="bth-btn bth-btn-secondary" id="bth-qr-download"><?php esc_html_e( 'Download as PNG', 'ald-business-tools' ); ?></button>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/qrcode@1.5.3/build/qrcode.min.js"></script>
<script>
(function () {
    'use strict';

    var input   = document.getElementById('bth-qr-input');
    var sizeSel = document.getElementById('bth-qr-size');
    var btnGen  = document.getElementById('bth-qr-generate');
    var canvas  = document.getElementById('bth-qr-canvas');
    var result  = document.getElementById('bth-qr-result');
    var btnDown = document.getElementById('bth-qr-download');
    var downWrap = document.getElementById('bth-qr-download-wrap');

    btnGen.addEventListener('click', function () {
        var text = input.value.trim();
        if (!text) {
            alert('<?php echo esc_js( __( 'Please enter text or a URL.', 'ald-business-tools' ) ); ?>');
            return;
        }

        var size = parseInt(sizeSel.value, 10) || 256;

        QRCode.toCanvas(canvas, text, {
            width: size,
            margin: 2,
            color: {
                dark: '#000000',
                light: '#ffffff'
            }
        }, function (error) {
            if (error) {
                alert('<?php echo esc_js( __( 'Failed to generate QR code. Please try again.', 'ald-business-tools' ) ); ?>');
                console.error(error);
                return;
            }
            result.style.display = 'block';
            downWrap.style.display = 'block';
        });
    });

    btnDown.addEventListener('click', function () {
        var dataUrl = canvas.toDataURL('image/png');
        var link = document.createElement('a');
        link.download = 'qrcode.png';
        link.href = dataUrl;
        document.body.appendChild(link);
        link.click();
        document.body.removeChild(link);
    });
})();
</script>
