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

$currencies = array(
    'USD' => 'USD ($)',
    'EUR' => 'EUR (€)',
    'GBP' => 'GBP (£)',
    'BDT' => 'BDT (৳)',
    'INR' => 'INR (₹)',
    'JPY' => 'JPY (¥)',
    'CNY' => 'CNY (¥)',
    'AUD' => 'AUD (A$)',
    'CAD' => 'CAD (C$)',
    'SAR' => 'SAR (﷼)',
    'AED' => 'AED (د.إ)',
    'MYR' => 'MYR (RM)',
    'SGD' => 'SGD (S$)',
    'THB' => 'THB (฿)',
    'PKR' => 'PKR (₨)',
    'KRW' => 'KRW (₩)',
    'BRL' => 'BRL (R$)',
    'NGN' => 'NGN (₦)',
    'EGP' => 'EGP (E£)',
    'LKR' => 'LKR (Rs)',
);
?>
<div class="bth-invoice-wrap">
    <form id="bth-invoice-form" class="bth-form">

        <!-- Row 1: Logo + Invoice Number / Date / Due Date -->
        <div class="bth-inv-row">
            <div class="bth-inv-col">
                <div class="bth-form-group">
                    <label class="bth-form-label"><?php esc_html_e( 'Company Logo', 'ald-business-tools' ); ?></label>
                    <div class="bth-logo-upload">
                        <input type="file" id="bth-inv-logo" accept="image/*" style="display:none;">
                        <button type="button" class="bth-btn bth-btn-secondary" id="bth-inv-logo-btn"><?php esc_html_e( 'Upload Logo', 'ald-business-tools' ); ?></button>
                        <img id="bth-inv-logo-preview" style="display:none; max-height:60px; margin-left:10px; vertical-align:middle;">
                        <button type="button" id="bth-inv-logo-remove" class="bth-inv-logo-remove" style="display:none; margin-left:4px; background:none; border:none; color:#dc2626; cursor:pointer; font-size:18px;">&times;</button>
                    </div>
                </div>
            </div>
            <div class="bth-inv-col bth-inv-col-right">
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
        </div>

        <!-- Row 2: From + Bill To -->
        <div class="bth-inv-row">
            <div class="bth-inv-col">
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
            <div class="bth-inv-col">
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
                <div class="bth-form-group">
                    <label class="bth-form-label" for="bth-inv-to-phone"><?php esc_html_e( 'Phone', 'ald-business-tools' ); ?></label>
                    <input type="text" class="bth-form-input" id="bth-inv-to-phone" placeholder="<?php esc_attr_e( '+1 234 567 890', 'ald-business-tools' ); ?>">
                </div>
            </div>
        </div>

        <!-- Currency Selector -->
        <div class="bth-form-group" style="max-width:200px;">
            <label class="bth-form-label" for="bth-inv-currency"><?php esc_html_e( 'Currency', 'ald-business-tools' ); ?></label>
            <select class="bth-form-select" id="bth-inv-currency">
                <?php foreach ( $currencies as $code => $label ) : ?>
                    <option value="<?php echo esc_attr( $code ); ?>" <?php selected( $code, 'USD' ); ?>><?php echo esc_html( $label ); ?></option>
                <?php endforeach; ?>
            </select>
        </div>

        <!-- Row 3: Line Items Table -->
        <h3><?php esc_html_e( 'Line Items', 'ald-business-tools' ); ?></h3>
        <table class="bth-table bth-inv-items-table" id="bth-inv-items">
            <thead>
                <tr>
                    <th><?php esc_html_e( 'Description', 'ald-business-tools' ); ?></th>
                    <th style="width:80px;"><?php esc_html_e( 'Qty', 'ald-business-tools' ); ?></th>
                    <th style="width:120px;"><?php esc_html_e( 'Rate', 'ald-business-tools' ); ?></th>
                    <th style="width:120px;"><?php esc_html_e( 'Amount', 'ald-business-tools' ); ?></th>
                    <th style="width:40px;"></th>
                </tr>
            </thead>
            <tbody>
                <tr class="bth-inv-item-row">
                    <td><input type="text" class="bth-form-input bth-inv-desc" placeholder="<?php esc_attr_e( 'Item description', 'ald-business-tools' ); ?>"></td>
                    <td><input type="number" class="bth-form-input bth-inv-qty" min="0" step="1" value="1"></td>
                    <td><input type="number" class="bth-form-input bth-inv-rate" min="0" step="0.01" value="0.00"></td>
                    <td><input type="text" class="bth-form-input bth-inv-amount" value="0.00" readonly></td>
                    <td><button type="button" class="bth-inv-remove-row" title="<?php esc_attr_e( 'Remove row', 'ald-business-tools' ); ?>">&times;</button></td>
                </tr>
            </tbody>
        </table>
        <div class="bth-form-actions" style="margin-bottom:20px;">
            <button type="button" class="bth-btn bth-btn-secondary" id="bth-inv-add-row"><?php esc_html_e( 'Add Row', 'ald-business-tools' ); ?></button>
        </div>

        <!-- Row 4: Notes + Totals -->
        <div class="bth-inv-row">
            <div class="bth-inv-col">
                <div class="bth-form-group">
                    <label class="bth-form-label" for="bth-inv-notes"><?php esc_html_e( 'Notes', 'ald-business-tools' ); ?></label>
                    <textarea class="bth-form-textarea" id="bth-inv-notes" rows="4" placeholder="<?php esc_attr_e( 'Additional notes for the client…', 'ald-business-tools' ); ?>"></textarea>
                </div>
            </div>
            <div class="bth-inv-col bth-inv-col-right">
                <div class="bth-inv-totals-box">
                    <div class="bth-inv-total-row">
                        <span><?php esc_html_e( 'Subtotal', 'ald-business-tools' ); ?></span>
                        <input type="text" class="bth-form-input bth-inv-total-input" id="bth-inv-subtotal" value="0.00" readonly>
                    </div>
                    <div class="bth-inv-total-row">
                        <span><?php esc_html_e( 'Tax Rate (%)', 'ald-business-tools' ); ?></span>
                        <input type="number" class="bth-form-input bth-inv-total-input" id="bth-inv-tax-rate" min="0" step="0.01" value="0">
                    </div>
                    <div class="bth-inv-total-row">
                        <span><?php esc_html_e( 'Discount', 'ald-business-tools' ); ?></span>
                        <input type="number" class="bth-form-input bth-inv-total-input" id="bth-inv-discount" min="0" step="0.01" value="0.00">
                    </div>
                    <div class="bth-inv-total-row">
                        <span><?php esc_html_e( 'Shipping', 'ald-business-tools' ); ?></span>
                        <input type="number" class="bth-form-input bth-inv-total-input" id="bth-inv-shipping" min="0" step="0.01" value="0.00">
                    </div>
                    <hr>
                    <div class="bth-inv-total-row bth-inv-grand-total-row">
                        <span><?php esc_html_e( 'Total', 'ald-business-tools' ); ?></span>
                        <input type="text" class="bth-form-input bth-inv-total-input" id="bth-inv-total" value="0.00" readonly>
                    </div>
                </div>
            </div>
        </div>

        <!-- Row 5: Terms + Amount Paid / Balance Due -->
        <div class="bth-inv-row">
            <div class="bth-inv-col">
                <div class="bth-form-group">
                    <label class="bth-form-label" for="bth-inv-terms"><?php esc_html_e( 'Terms & Conditions', 'ald-business-tools' ); ?></label>
                    <textarea class="bth-form-textarea" id="bth-inv-terms" rows="4" placeholder="<?php esc_attr_e( 'Payment terms, late fees, etc.', 'ald-business-tools' ); ?>"></textarea>
                </div>
            </div>
            <div class="bth-inv-col bth-inv-col-right">
                <div class="bth-inv-totals-box">
                    <div class="bth-inv-total-row bth-inv-grand-total-row">
                        <span><?php esc_html_e( 'Total', 'ald-business-tools' ); ?></span>
                        <input type="text" class="bth-form-input bth-inv-total-input" id="bth-inv-total-2" value="0.00" readonly>
                    </div>
                    <div class="bth-inv-total-row">
                        <span><?php esc_html_e( 'Amount Paid', 'ald-business-tools' ); ?></span>
                        <input type="number" class="bth-form-input bth-inv-total-input" id="bth-inv-paid" min="0" step="0.01" value="0.00">
                    </div>
                    <hr>
                    <div class="bth-inv-total-row bth-inv-balance-row">
                        <span><?php esc_html_e( 'Balance Due', 'ald-business-tools' ); ?></span>
                        <input type="text" class="bth-form-input bth-inv-total-input" id="bth-inv-balance" value="0.00" readonly>
                    </div>
                </div>
            </div>
        </div>

        <div class="bth-form-actions">
            <button type="button" class="bth-btn bth-btn-primary" id="bth-inv-generate"><?php esc_html_e( 'Generate Invoice', 'ald-business-tools' ); ?></button>
            <button type="button" class="bth-btn bth-btn-secondary" id="bth-inv-download" style="display:none;"><?php esc_html_e( 'Download PDF', 'ald-business-tools' ); ?></button>
        </div>
    </form>

    <div id="bth-inv-preview" class="bth-invoice-preview" style="display:none;"></div>
</div>

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
    var shippingInput  = document.getElementById('bth-inv-shipping');
    var paidInput      = document.getElementById('bth-inv-paid');
    var subtotalInput  = document.getElementById('bth-inv-subtotal');
    var totalInput     = document.getElementById('bth-inv-total');
    var total2Input    = document.getElementById('bth-inv-total-2');
    var balanceInput   = document.getElementById('bth-inv-balance');
    var currencySelect = document.getElementById('bth-inv-currency');

    // Logo upload
    var logoInput   = document.getElementById('bth-inv-logo');
    var logoBtn     = document.getElementById('bth-inv-logo-btn');
    var logoPreview = document.getElementById('bth-inv-logo-preview');
    var logoRemove  = document.getElementById('bth-inv-logo-remove');
    var logoDataUrl = '';

    logoBtn.addEventListener('click', function () { logoInput.click(); });
    logoInput.addEventListener('change', function () {
        var file = this.files[0];
        if (!file) return;
        var reader = new FileReader();
        reader.onload = function (e) {
            logoDataUrl = e.target.result;
            logoPreview.src = logoDataUrl;
            logoPreview.style.display = 'inline';
            logoRemove.style.display = 'inline';
        };
        reader.readAsDataURL(file);
    });
    logoRemove.addEventListener('click', function () {
        logoDataUrl = '';
        logoInput.value = '';
        logoPreview.src = '';
        logoPreview.style.display = 'none';
        logoRemove.style.display = 'none';
    });

    function fmt(val) {
        var n = parseFloat(val);
        if (isNaN(n)) n = 0;
        return n.toFixed(2);
    }

    function getCurrency() {
        return currencySelect ? currencySelect.value : 'USD';
    }

    function fmtMoney(val) {
        return getCurrency() + ' ' + fmt(val);
    }

    function recalcRow(row) {
        var qty    = parseFloat(row.querySelector('.bth-inv-qty').value) || 0;
        var rate   = parseFloat(row.querySelector('.bth-inv-rate').value) || 0;
        var amount = qty * rate;
        row.querySelector('.bth-inv-amount').value = fmt(amount);
        return amount;
    }

    function recalcTotals() {
        var rows = itemsTable.querySelectorAll('.bth-inv-item-row');
        var subtotal = 0;
        rows.forEach(function (row) {
            subtotal += recalcRow(row);
        });
        subtotalInput.value = fmt(subtotal);

        var taxRate    = parseFloat(taxRateInput.value) || 0;
        var discount   = parseFloat(discountInput.value) || 0;
        var shipping   = parseFloat(shippingInput.value) || 0;
        var taxAmount  = subtotal * (taxRate / 100);
        var total      = subtotal + taxAmount + shipping - discount;
        if (total < 0) total = 0;

        totalInput.value = fmt(total);
        total2Input.value = fmt(total);

        var paid = parseFloat(paidInput.value) || 0;
        var balance = total - paid;
        if (balance < 0) balance = 0;
        balanceInput.value = fmt(balance);
    }

    function addRow() {
        var tr = document.createElement('tr');
        tr.className = 'bth-inv-item-row';
        tr.innerHTML =
            '<td><input type="text" class="bth-form-input bth-inv-desc" placeholder="<?php esc_attr_e( 'Item description', 'ald-business-tools' ); ?>"></td>' +
            '<td><input type="number" class="bth-form-input bth-inv-qty" min="0" step="1" value="1"></td>' +
            '<td><input type="number" class="bth-form-input bth-inv-rate" min="0" step="0.01" value="0.00"></td>' +
            '<td><input type="text" class="bth-form-input bth-inv-amount" value="0.00" readonly></td>' +
            '<td><button type="button" class="bth-inv-remove-row" title="<?php esc_attr_e( 'Remove row', 'ald-business-tools' ); ?>">&times;</button></td>';
        itemsTable.appendChild(tr);
        bindRowEvents(tr);
    }

    function bindRowEvents(row) {
        row.querySelector('.bth-inv-qty').addEventListener('input', recalcTotals);
        row.querySelector('.bth-inv-rate').addEventListener('input', recalcTotals);
        row.querySelector('.bth-inv-remove-row').addEventListener('click', function () {
            var allRows = itemsTable.querySelectorAll('.bth-inv-item-row');
            if (allRows.length > 1) {
                row.remove();
            } else {
                row.querySelector('.bth-inv-desc').value = '';
                row.querySelector('.bth-inv-qty').value = '1';
                row.querySelector('.bth-inv-rate').value = '0.00';
                row.querySelector('.bth-inv-amount').value = '0.00';
            }
            recalcTotals();
        });
    }

    itemsTable.querySelectorAll('.bth-inv-item-row').forEach(bindRowEvents);
    addRowBtn.addEventListener('click', addRow);
    taxRateInput.addEventListener('input', recalcTotals);
    discountInput.addEventListener('input', recalcTotals);
    shippingInput.addEventListener('input', recalcTotals);
    paidInput.addEventListener('input', recalcTotals);
    if (currencySelect) currencySelect.addEventListener('change', buildPreview);

    function esc(str) {
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
        var toPhone    = document.getElementById('bth-inv-to-phone').value;
        var subtotal   = subtotalInput.value;
        var taxRate    = taxRateInput.value;
        var discount   = discountInput.value;
        var shipping   = shippingInput.value;
        var total      = totalInput.value;
        var paid       = paidInput.value;
        var balance    = balanceInput.value;
        var notes      = document.getElementById('bth-inv-notes').value;
        var terms      = document.getElementById('bth-inv-terms').value;
        var currency   = getCurrency();

        var rows = itemsTable.querySelectorAll('.bth-inv-item-row');
        var itemsHtml = '';
        rows.forEach(function (row) {
            var desc   = row.querySelector('.bth-inv-desc').value;
            var qty    = row.querySelector('.bth-inv-qty').value;
            var rate   = row.querySelector('.bth-inv-rate').value;
            var amount = row.querySelector('.bth-inv-amount').value;
            if (desc || parseFloat(qty) > 0) {
                itemsHtml += '<tr>' +
                    '<td>' + esc(desc) + '</td>' +
                    '<td style="text-align:center;">' + esc(qty) + '</td>' +
                    '<td style="text-align:right;">' + currency + ' ' + fmt(rate) + '</td>' +
                    '<td style="text-align:right;">' + currency + ' ' + fmt(amount) + '</td>' +
                '</tr>';
            }
        });

        var subtotalNum = parseFloat(subtotal) || 0;
        var taxAmount   = subtotalNum * (parseFloat(taxRate) || 0) / 100;
        var discountNum = parseFloat(discount) || 0;
        var shippingNum = parseFloat(shipping) || 0;
        var totalNum    = parseFloat(total) || 0;
        var paidNum     = parseFloat(paid) || 0;
        var balanceNum  = parseFloat(balance) || 0;

        var html = '<div class="bth-inv-pdf-wrap">' +

            // Row 1: Logo + Invoice Number / Date / Due Date
            '<div class="bth-inv-pdf-row">' +
                '<div class="bth-inv-pdf-col">' +
                    (logoDataUrl ? '<img src="' + logoDataUrl + '" class="bth-inv-pdf-logo">' : '') +
                '</div>' +
                '<div class="bth-inv-pdf-col bth-inv-pdf-right">' +
                    '<h1 class="bth-inv-pdf-title"><?php esc_html_e( 'INVOICE', 'ald-business-tools' ); ?></h1>' +
                    '<p class="bth-inv-pdf-number">#' + esc(invNumber) + '</p>' +
                    '<p><strong><?php esc_html_e( 'Invoice Date:', 'ald-business-tools' ); ?></strong> ' + esc(invDate) + '</p>' +
                    (invDue ? '<p><strong><?php esc_html_e( 'Due Date:', 'ald-business-tools' ); ?></strong> ' + esc(invDue) + '</p>' : '') +
                '</div>' +
            '</div>' +

            // Row 2: From + Bill To
            '<div class="bth-inv-pdf-row">' +
                '<div class="bth-inv-pdf-col">' +
                    '<p><strong><?php esc_html_e( 'From', 'ald-business-tools' ); ?></strong></p>' +
                    '<p><strong>' + esc(fromName) + '</strong></p>' +
                    '<p>' + esc(fromAddr).replace(/\n/g, '<br>') + '</p>' +
                    '<p>' + esc(fromEmail) + '</p>' +
                    '<p>' + esc(fromPhone) + '</p>' +
                '</div>' +
                '<div class="bth-inv-pdf-col">' +
                    '<p><strong><?php esc_html_e( 'Bill To', 'ald-business-tools' ); ?></strong></p>' +
                    '<p><strong>' + esc(toName) + '</strong></p>' +
                    '<p>' + esc(toAddr).replace(/\n/g, '<br>') + '</p>' +
                    '<p>' + esc(toEmail) + '</p>' +
                    '<p>' + esc(toPhone) + '</p>' +
                '</div>' +
            '</div>' +

            // Row 3: Items Table
            '<table class="bth-inv-pdf-table">' +
                '<thead><tr>' +
                    '<th><?php esc_html_e( 'Description', 'ald-business-tools' ); ?></th>' +
                    '<th style="text-align:center;width:60px;"><?php esc_html_e( 'Qty', 'ald-business-tools' ); ?></th>' +
                    '<th style="text-align:right;width:100px;"><?php esc_html_e( 'Rate', 'ald-business-tools' ); ?></th>' +
                    '<th style="text-align:right;width:100px;"><?php esc_html_e( 'Amount', 'ald-business-tools' ); ?></th>' +
                '</tr></thead>' +
                '<tbody>' + itemsHtml + '</tbody>' +
            '</table>' +

            // Row 4: Notes + Totals
            '<div class="bth-inv-pdf-row">' +
                '<div class="bth-inv-pdf-col">' +
                    (notes ? '<div class="bth-inv-pdf-notes"><p><strong><?php esc_html_e( 'Notes', 'ald-business-tools' ); ?></strong></p><p>' + esc(notes).replace(/\n/g, '<br>') + '</p></div>' : '') +
                '</div>' +
                '<div class="bth-inv-pdf-col bth-inv-pdf-right">' +
                    '<table class="bth-inv-pdf-summary">' +
                        '<tr><td><?php esc_html_e( 'Subtotal', 'ald-business-tools' ); ?></td><td>' + currency + ' ' + fmt(subtotalNum) + '</td></tr>' +
                        '<tr><td><?php esc_html_e( 'Tax', 'ald-business-tools' ); ?> (' + esc(taxRate) + '%)</td><td>' + currency + ' ' + fmt(taxAmount) + '</td></tr>' +
                        '<tr><td><?php esc_html_e( 'Discount', 'ald-business-tools' ); ?></td><td>-' + currency + ' ' + fmt(discountNum) + '</td></tr>' +
                        '<tr><td><?php esc_html_e( 'Shipping', 'ald-business-tools' ); ?></td><td>' + currency + ' ' + fmt(shippingNum) + '</td></tr>' +
                        '<tr class="bth-inv-pdf-grand-total"><td><strong><?php esc_html_e( 'Total', 'ald-business-tools' ); ?></strong></td><td><strong>' + currency + ' ' + fmt(totalNum) + '</strong></td></tr>' +
                    '</table>' +
                '</div>' +
            '</div>' +

            // Row 5: Terms + Amount Paid / Balance Due
            '<div class="bth-inv-pdf-row">' +
                '<div class="bth-inv-pdf-col">' +
                    (terms ? '<div class="bth-inv-pdf-terms"><p><strong><?php esc_html_e( 'Terms & Conditions', 'ald-business-tools' ); ?></strong></p><p>' + esc(terms).replace(/\n/g, '<br>') + '</p></div>' : '') +
                '</div>' +
                '<div class="bth-inv-pdf-col bth-inv-pdf-right">' +
                    '<table class="bth-inv-pdf-summary">' +
                        '<tr class="bth-inv-pdf-grand-total"><td><strong><?php esc_html_e( 'Total', 'ald-business-tools' ); ?></strong></td><td><strong>' + currency + ' ' + fmt(totalNum) + '</strong></td></tr>' +
                        '<tr><td><?php esc_html_e( 'Amount Paid', 'ald-business-tools' ); ?></td><td>' + currency + ' ' + fmt(paidNum) + '</td></tr>' +
                        '<tr class="bth-inv-pdf-balance"><td><strong><?php esc_html_e( 'Balance Due', 'ald-business-tools' ); ?></strong></td><td><strong>' + currency + ' ' + fmt(balanceNum) + '</strong></td></tr>' +
                    '</table>' +
                '</div>' +
            '</div>' +

        '</div>';

        previewEl.innerHTML = html;
        previewEl.style.display = 'block';
        downloadBtn.style.display = 'inline-block';
    }

    generateBtn.addEventListener('click', buildPreview);

    downloadBtn.addEventListener('click', function () {
        if (typeof window.jspdf === 'undefined' || typeof html2canvas === 'undefined') {
            alert('<?php echo esc_js( __( 'PDF library not loaded. Please refresh the page and try again.', 'ald-business-tools' ) ); ?>');
            return;
        }

        var element = previewEl.querySelector('.bth-inv-pdf-wrap');
        if (!element) {
            alert('<?php echo esc_js( __( 'Please generate the invoice first.', 'ald-business-tools' ) ); ?>');
            return;
        }

        // Move element to a visible but off-screen container for html2canvas
        var captureContainer = document.createElement('div');
        captureContainer.style.cssText = 'position:absolute; top:0; left:0; width:794px; background:#fff; z-index:-1; overflow:visible;';
        // Clone the element so the original stays in place
        var clone = element.cloneNode(true);
        captureContainer.appendChild(clone);
        document.body.appendChild(captureContainer);

        var invNum = document.getElementById('bth-inv-number').value || 'invoice';

        html2canvas(clone, {
            scale: 2,
            useCORS: true,
            logging: false,
            backgroundColor: '#ffffff',
            width: 794,
            windowWidth: 794
        }).then(function (canvas) {
            var imgData = canvas.toDataURL('image/png');
            var pdf = new window.jspdf.jsPDF('p', 'mm', 'a4');
            var pdfWidth = pdf.internal.pageSize.getWidth();
            var pdfHeight = pdf.internal.pageSize.getHeight();
            var margin = 10;
            var contentWidth = pdfWidth - (margin * 2);
            var contentHeight = pdfHeight - (margin * 2);
            var imgWidth = canvas.width;
            var imgHeight = canvas.height;
            var ratio = Math.min(contentWidth / imgWidth, contentHeight / imgHeight);
            var imgX = margin + (contentWidth - imgWidth * ratio) / 2;
            var imgY = margin;

            pdf.addImage(imgData, 'PNG', imgX, imgY, imgWidth * ratio, imgHeight * ratio);
            pdf.save(invNum + '.pdf');

            document.body.removeChild(captureContainer);
        }).catch(function (err) {
            console.error('PDF generation error:', err);
            alert('<?php echo esc_js( __( 'Failed to generate PDF. Please try again.', 'ald-business-tools' ) ); ?>');
            if (captureContainer.parentNode) {
                document.body.removeChild(captureContainer);
            }
        });
    });

    recalcTotals();
})();
</script>
