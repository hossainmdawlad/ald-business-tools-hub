<?php
/**
 * Invoice Generator Tool
 *
 * @package ALD_Business_Tools_Hub
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

$invoice_prefix = get_option( 'bth_invoice_prefix', 'INV-' );
$invoice_number = $invoice_prefix . wp_rand( 1000, 9999 );
$today           = current_time( 'Y-m-d' );
?>
<div class="bth-invoice-wrap">
    <form id="bth-invoice-form" class="bth-form">
        <h3><?php esc_html_e( 'Invoice Details', 'ald-business-tools' ); ?></h3>
        <div class="bth-form-row">
            <div class="bth-form-group">
                <label class="bth-form-label" for="bth-inv-number"><?php esc_html_e( 'Invoice Number', 'ald-business-tools' ); ?></label>
                <input type="text" class="bth-form-input" id="bth-inv-number" value="<?php echo esc_attr( $invoice_number ); ?>">
            </div>
            <div class="bth-form-group">
                <label class="bth-form-label" for="bth-inv-date"><?php esc_html_e( 'Invoice Date', 'ald-business-tools' ); ?></label>
                <input type="date" class="bth-form-input" id="bth-inv-date" value="<?php echo esc_attr( $today ); ?>">
            </div>
            <div class="bth-form-group">
                <label class="bth-form-label" for="bth-inv-due"><?php esc_html_e( 'Due Date', 'ald-business-tools' ); ?></label>
                <input type="date" class="bth-form-input" id="bth-inv-due" value="">
            </div>
        </div>

        <div class="bth-form-columns">
            <div class="bth-form-column">
                <h3><?php esc_html_e( 'From', 'ald-business-tools' ); ?></h3>
                <div class="bth-form-group">
                    <label class="bth-form-label" for="bth-inv-from-name"><?php esc_html_e( 'Company Name', 'ald-business-tools' ); ?></label>
                    <input type="text" class="bth-form-input" id="bth-inv-from-name" placeholder="<?php esc_attr_e( 'Your Company Name', 'ald-business-tools' ); ?>">
                </div>
                <div class="bth-form-group">
                    <label class="bth-form-label" for="bth-inv-from-address"><?php esc_html_e( 'Address', 'ald-business-tools' ); ?></label>
                    <textarea class="bth-form-textarea" id="bth-inv-from-address" rows="3" placeholder="<?php esc_attr_e( 'Company Address', 'ald-business-tools' ); ?>"></textarea>
                </div>
                <div class="bth-form-group">
                    <label class="bth-form-label" for="bth-inv-from-email"><?php esc_html_e( 'Email', 'ald-business-tools' ); ?></label>
                    <input type="email" class="bth-form-input" id="bth-inv-from-email" placeholder="<?php esc_attr_e( 'company@example.com', 'ald-business-tools' ); ?>">
                </div>
                <div class="bth-form-group">
                    <label class="bth-form-label" for="bth-inv-from-phone"><?php esc_html_e( 'Phone', 'ald-business-tools' ); ?></label>
                    <input type="text" class="bth-form-input" id="bth-inv-from-phone" placeholder="<?php esc_attr_e( '+1 234 567 890', 'ald-business-tools' ); ?>">
                </div>
            </div>

            <div class="bth-form-column">
                <h3><?php esc_html_e( 'Bill To', 'ald-business-tools' ); ?></h3>
                <div class="bth-form-group">
                    <label class="bth-form-label" for="bth-inv-to-name"><?php esc_html_e( 'Client Name', 'ald-business-tools' ); ?></label>
                    <input type="text" class="bth-form-input" id="bth-inv-to-name" placeholder="<?php esc_attr_e( 'Client Name', 'ald-business-tools' ); ?>">
                </div>
                <div class="bth-form-group">
                    <label class="bth-form-label" for="bth-inv-to-address"><?php esc_html_e( 'Address', 'ald-business-tools' ); ?></label>
                    <textarea class="bth-form-textarea" id="bth-inv-to-address" rows="3" placeholder="<?php esc_attr_e( 'Client Address', 'ald-business-tools' ); ?>"></textarea>
                </div>
                <div class="bth-form-group">
                    <label class="bth-form-label" for="bth-inv-to-email"><?php esc_html_e( 'Email', 'ald-business-tools' ); ?></label>
                    <input type="email" class="bth-form-input" id="bth-inv-to-email" placeholder="<?php esc_attr_e( 'client@example.com', 'ald-business-tools' ); ?>">
                </div>
            </div>
        </div>

        <h3><?php esc_html_e( 'Line Items', 'ald-business-tools' ); ?></h3>
        <table class="bth-table" id="bth-inv-items">
            <thead>
                <tr>
                    <th><?php esc_html_e( 'Description', 'ald-business-tools' ); ?></th>
                    <th><?php esc_html_e( 'Qty', 'ald-business-tools' ); ?></th>
                    <th><?php esc_html_e( 'Rate', 'ald-business-tools' ); ?></th>
                    <th><?php esc_html_e( 'Amount', 'ald-business-tools' ); ?></th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                <tr class="bth-inv-item-row">
                    <td><input type="text" class="bth-form-input bth-inv-desc" placeholder="<?php esc_attr_e( 'Item description', 'ald-business-tools' ); ?>"></td>
                    <td><input type="number" class="bth-form-input bth-inv-qty" min="0" step="1" value="1"></td>
                    <td><input type="number" class="bth-form-input bth-inv-rate" min="0" step="0.01" value="0.00"></td>
                    <td><input type="text" class="bth-form-input bth-inv-amount" value="0.00" readonly></td>
                    <td><button type="button" class="bth-btn bth-btn-secondary bth-inv-remove-row" title="<?php esc_attr_e( 'Remove row', 'ald-business-tools' ); ?>">&times;</button></td>
                </tr>
            </tbody>
        </table>
        <div class="bth-form-actions">
            <button type="button" class="bth-btn bth-btn-secondary" id="bth-inv-add-row"><?php esc_html_e( 'Add Row', 'ald-business-tools' ); ?></button>
        </div>

        <div class="bth-form-row bth-inv-totals-row">
            <div class="bth-form-group">
                <label class="bth-form-label" for="bth-inv-subtotal"><?php esc_html_e( 'Subtotal', 'ald-business-tools' ); ?></label>
                <input type="text" class="bth-form-input" id="bth-inv-subtotal" value="0.00" readonly>
            </div>
            <div class="bth-form-group">
                <label class="bth-form-label" for="bth-inv-tax-rate"><?php esc_html_e( 'Tax Rate (%)', 'ald-business-tools' ); ?></label>
                <input type="number" class="bth-form-input" id="bth-inv-tax-rate" min="0" step="0.01" value="0">
            </div>
            <div class="bth-form-group">
                <label class="bth-form-label" for="bth-inv-discount"><?php esc_html_e( 'Discount', 'ald-business-tools' ); ?></label>
                <input type="number" class="bth-form-input" id="bth-inv-discount" min="0" step="0.01" value="0.00">
            </div>
            <div class="bth-form-group">
                <label class="bth-form-label" for="bth-inv-total"><?php esc_html_e( 'Total', 'ald-business-tools' ); ?></label>
                <input type="text" class="bth-form-input" id="bth-inv-total" value="0.00" readonly>
            </div>
        </div>

        <div class="bth-form-group">
            <label class="bth-form-label" for="bth-inv-notes"><?php esc_html_e( 'Notes', 'ald-business-tools' ); ?></label>
            <textarea class="bth-form-textarea" id="bth-inv-notes" rows="3" placeholder="<?php esc_attr_e( 'Additional notes for the client…', 'ald-business-tools' ); ?>"></textarea>
        </div>

        <div class="bth-form-group">
            <label class="bth-form-label" for="bth-inv-terms"><?php esc_html_e( 'Terms & Conditions', 'ald-business-tools' ); ?></label>
            <textarea class="bth-form-textarea" id="bth-inv-terms" rows="3" placeholder="<?php esc_attr_e( 'Payment terms, late fees, etc.', 'ald-business-tools' ); ?>"></textarea>
        </div>

        <div class="bth-form-actions">
            <button type="button" class="bth-btn bth-btn-primary" id="bth-inv-generate"><?php esc_html_e( 'Generate Invoice', 'ald-business-tools' ); ?></button>
            <button type="button" class="bth-btn bth-btn-secondary" id="bth-inv-download" style="display:none;"><?php esc_html_e( 'Download PDF', 'ald-business-tools' ); ?></button>
        </div>
    </form>

    <div id="bth-inv-preview" class="bth-invoice-preview" style="display:none;"></div>
</div>

<script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.10.1/html2pdf.bundle.min.js"></script>
<script>
(function () {
    'use strict';

    var itemsTable  = document.getElementById('bth-inv-items').querySelector('tbody');
    var addRowBtn   = document.getElementById('bth-inv-add-row');
    var generateBtn = document.getElementById('bth-inv-generate');
    var downloadBtn = document.getElementById('bth-inv-download');
    var previewEl   = document.getElementById('bth-inv-preview');

    var taxRateInput   = document.getElementById('bth-inv-tax-rate');
    var discountInput  = document.getElementById('bth-inv-discount');
    var subtotalInput  = document.getElementById('bth-inv-subtotal');
    var totalInput     = document.getElementById('bth-inv-total');

    function formatCurrency(val) {
        return parseFloat(val).toFixed(2);
    }

    function recalcRow(row) {
        var qty    = parseFloat(row.querySelector('.bth-inv-qty').value) || 0;
        var rate   = parseFloat(row.querySelector('.bth-inv-rate').value) || 0;
        var amount = qty * rate;
        row.querySelector('.bth-inv-amount').value = formatCurrency(amount);
        return amount;
    }

    function recalcTotals() {
        var rows = itemsTable.querySelectorAll('.bth-inv-item-row');
        var subtotal = 0;
        rows.forEach(function (row) {
            subtotal += recalcRow(row);
        });
        subtotalInput.value = formatCurrency(subtotal);

        var taxRate   = parseFloat(taxRateInput.value) || 0;
        var discount  = parseFloat(discountInput.value) || 0;
        var taxAmount = subtotal * (taxRate / 100);
        var total     = subtotal + taxAmount - discount;
        if (total < 0) { total = 0; }
        totalInput.value = formatCurrency(total);
    }

    function addRow() {
        var tr = document.createElement('tr');
        tr.className = 'bth-inv-item-row';
        tr.innerHTML =
            '<td><input type="text" class="bth-form-input bth-inv-desc" placeholder="<?php esc_attr_e( 'Item description', 'ald-business-tools' ); ?>"></td>' +
            '<td><input type="number" class="bth-form-input bth-inv-qty" min="0" step="1" value="1"></td>' +
            '<td><input type="number" class="bth-form-input bth-inv-rate" min="0" step="0.01" value="0.00"></td>' +
            '<td><input type="text" class="bth-form-input bth-inv-amount" value="0.00" readonly></td>' +
            '<td><button type="button" class="bth-btn bth-btn-secondary bth-inv-remove-row" title="<?php esc_attr_e( 'Remove row', 'ald-business-tools' ); ?>">&times;</button></td>';
        itemsTable.appendChild(tr);
        bindRowEvents(tr);
    }

    function bindRowEvents(row) {
        var qtyInput   = row.querySelector('.bth-inv-qty');
        var rateInput  = row.querySelector('.bth-inv-rate');
        var removeBtn  = row.querySelector('.bth-inv-remove-row');

        qtyInput.addEventListener('input', recalcTotals);
        rateInput.addEventListener('input', recalcTotals);

        removeBtn.addEventListener('click', function () {
            var allRows = itemsTable.querySelectorAll('.bth-inv-item-row');
            if (allRows.length > 1) {
                row.remove();
                recalcTotals();
            } else {
                row.querySelector('.bth-inv-desc').value = '';
                row.querySelector('.bth-inv-qty').value = '1';
                row.querySelector('.bth-inv-rate').value = '0.00';
                row.querySelector('.bth-inv-amount').value = '0.00';
                recalcTotals();
            }
        });
    }

    // Bind existing row
    itemsTable.querySelectorAll('.bth-inv-item-row').forEach(bindRowEvents);

    addRowBtn.addEventListener('click', addRow);
    taxRateInput.addEventListener('input', recalcTotals);
    discountInput.addEventListener('input', recalcTotals);

    function escapeHtml(str) {
        var div = document.createElement('div');
        div.appendChild(document.createTextNode(str));
        return div.innerHTML;
    }

    function buildPreview() {
        var invNumber  = document.getElementById('bth-inv-number').value;
        var invDate    = document.getElementById('bth-inv-date').value;
        var invDue     = document.getElementById('bth-inv-due').value;
        var fromName   = document.getElementById('bth-inv-from-name').value;
        var fromAddr   = document.getElementById('bth-inv-from-address').value;
        var fromEmail  = document.getElementById('bth-inv-from-email').value;
        var fromPhone  = document.getElementById('bth-inv-from-phone').value;
        var toName     = document.getElementById('bth-inv-to-name').value;
        var toAddr     = document.getElementById('bth-inv-to-address').value;
        var toEmail    = document.getElementById('bth-inv-to-email').value;
        var subtotal   = subtotalInput.value;
        var taxRate    = taxRateInput.value;
        var discount   = discountInput.value;
        var total      = totalInput.value;
        var notes      = document.getElementById('bth-inv-notes').value;
        var terms      = document.getElementById('bth-inv-terms').value;

        var rows = itemsTable.querySelectorAll('.bth-inv-item-row');
        var itemsHtml = '';
        rows.forEach(function (row) {
            var desc   = row.querySelector('.bth-inv-desc').value;
            var qty    = row.querySelector('.bth-inv-qty').value;
            var rate   = row.querySelector('.bth-inv-rate').value;
            var amount = row.querySelector('.bth-inv-amount').value;
            if (desc || parseFloat(qty) > 0) {
                itemsHtml += '<tr>' +
                    '<td>' + escapeHtml(desc) + '</td>' +
                    '<td>' + escapeHtml(qty) + '</td>' +
                    '<td>' + formatCurrency(parseFloat(rate) || 0) + '</td>' +
                    '<td>' + formatCurrency(parseFloat(amount) || 0) + '</td>' +
                '</tr>';
            }
        });

        var taxAmount = (parseFloat(subtotal) || 0) * (parseFloat(taxRate) || 0) / 100;

        var html = '<div class="bth-inv-preview-inner">' +
            '<div class="bth-inv-header">' +
                '<h2><?php esc_html_e( 'INVOICE', 'ald-business-tools' ); ?></h2>' +
                '<div class="bth-inv-meta">' +
                    '<p><strong>#' + escapeHtml(invNumber) + '</strong></p>' +
                    '<p><?php esc_html_e( 'Date:', 'ald-business-tools' ); ?> ' + escapeHtml(invDate) + '</p>' +
                    (invDue ? '<p><?php esc_html_e( 'Due:', 'ald-business-tools' ); ?> ' + escapeHtml(invDue) + '</p>' : '') +
                '</div>' +
            '</div>' +

            '<div class="bth-inv-parties">' +
                '<div class="bth-inv-from">' +
                    '<h4><?php esc_html_e( 'From', 'ald-business-tools' ); ?></h4>' +
                    '<p><strong>' + escapeHtml(fromName) + '</strong></p>' +
                    '<p>' + escapeHtml(fromAddr).replace(/\n/g, '<br>') + '</p>' +
                    '<p>' + escapeHtml(fromEmail) + '</p>' +
                    '<p>' + escapeHtml(fromPhone) + '</p>' +
                '</div>' +
                '<div class="bth-inv-to">' +
                    '<h4><?php esc_html_e( 'Bill To', 'ald-business-tools' ); ?></h4>' +
                    '<p><strong>' + escapeHtml(toName) + '</strong></p>' +
                    '<p>' + escapeHtml(toAddr).replace(/\n/g, '<br>') + '</p>' +
                    '<p>' + escapeHtml(toEmail) + '</p>' +
                '</div>' +
            '</div>' +

            '<table class="bth-inv-items-table">' +
                '<thead><tr>' +
                    '<th><?php esc_html_e( 'Description', 'ald-business-tools' ); ?></th>' +
                    '<th><?php esc_html_e( 'Qty', 'ald-business-tools' ); ?></th>' +
                    '<th><?php esc_html_e( 'Rate', 'ald-business-tools' ); ?></th>' +
                    '<th><?php esc_html_e( 'Amount', 'ald-business-tools' ); ?></th>' +
                '</tr></thead>' +
                '<tbody>' + itemsHtml + '</tbody>' +
            '</table>' +

            '<div class="bth-inv-summary">' +
                '<p><span><?php esc_html_e( 'Subtotal:', 'ald-business-tools' ); ?></span> <span>' + formatCurrency(parseFloat(subtotal) || 0) + '</span></p>' +
                '<p><span><?php esc_html_e( 'Tax', 'ald-business-tools' ); ?> (' + escapeHtml(taxRate) + '%):</span> <span>' + formatCurrency(taxAmount) + '</span></p>' +
                '<p><span><?php esc_html_e( 'Discount:', 'ald-business-tools' ); ?></span> <span>-' + formatCurrency(parseFloat(discount) || 0) + '</span></p>' +
                '<p class="bth-inv-grand-total"><span><?php esc_html_e( 'Total:', 'ald-business-tools' ); ?></span> <span>' + formatCurrency(parseFloat(total) || 0) + '</span></p>' +
            '</div>' +

            (notes ? '<div class="bth-inv-notes-section"><h4><?php esc_html_e( 'Notes', 'ald-business-tools' ); ?></h4><p>' + escapeHtml(notes).replace(/\n/g, '<br>') + '</p></div>' : '') +
            (terms ? '<div class="bth-inv-terms-section"><h4><?php esc_html_e( 'Terms & Conditions', 'ald-business-tools' ); ?></h4><p>' + escapeHtml(terms).replace(/\n/g, '<br>') + '</p></div>' : '') +
        '</div>';

        previewEl.innerHTML = html;
        previewEl.style.display = 'block';
        downloadBtn.style.display = 'inline-block';
    }

    generateBtn.addEventListener('click', buildPreview);

    downloadBtn.addEventListener('click', function () {
        if (typeof html2pdf === 'undefined') {
            alert('<?php echo esc_js( __( 'PDF library not loaded. Please wait and try again.', 'ald-business-tools' ) ); ?>');
            return;
        }
        var element = previewEl.querySelector('.bth-inv-preview-inner') || previewEl;
        var opt = {
            margin:       10,
            filename:     document.getElementById('bth-inv-number').value + '.pdf',
            image:        { type: 'jpeg', quality: 0.98 },
            html2canvas:  { scale: 2 },
            jsPDF:        { unit: 'mm', format: 'a4', orientation: 'portrait' }
        };
        html2pdf().set(opt).from(element).save();
    });

    // Initial calc
    recalcTotals();
})();
</script>
