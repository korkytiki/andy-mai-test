<h2>Invoice</h2>

<div class="row">
	<div class="col-md-9">
		<h3 class="top-label">Create Invoice</h3>
		<hr />
		<form>
			<input class="form-control" type="hidden" id="hdnInvoiceID">
			<div class="row">
				<div class="col-md-4">
					<div class="form-group">
						<label for="exampleFormControlSelect1">Customer</label>
						<select class="form-control" id="ddlCustomers">
						<?php foreach($customers as $customer): ?>
							<option value="<?php echo $customer->ID; ?>"><?php echo $customer->name; ?></option>
						<?php endforeach; ?>
						</select>
					</div>
				</div>
				<div class="col-md-2">
					<div class="form-group">
						<label for="exampleFormControlInput1">Invoice Number</label>
						<input type="text" class="form-control" id="txtInvoiceNumber" maxlength="255" placeholder="####" />
					</div>
				</div>
			</div>

			<h4>Items</h4>

			<table class="table table-striped table-sm" id="tblItems">
				<thead>
					<tr>
						<th scope="col">Product</th>
						<th scope="col" style="width: 120px;">Price</th>
						<th scope="col" style="width: 100px;">Qty</th>
						<th scope="col" style="width: 120px;">Total</th>
						<th scope="col" style="width: 52px;"></th>
					</tr>
					<tr class="main-item-detail item-detail table-success">
						<th scope="col">
							<select class="form-control ddlItemProducts" id="ddlProducts">
							<?php foreach($products as $product): ?>
								<option value="<?php echo $product->ID; ?>" data-price="<?php echo $product->price; ?>" data-qty="<?php echo $product->qty; ?>"><?php echo $product->name; ?></option>
							<?php endforeach; ?>
							</select>
						</th>
						<th scope="col" class="number-label">
							<span class="item-detail-price"></span>
						</th>
						<th scope="col" class="number-label">
							<input class="form-control txtItemQTY" type="number" placeholder="QTY" min="1" max="9999" step="1" id="txtQty">
						</th>
						<th scope="col" class="number-label">
							<span class="item-detail-total"></span>
						</th>
						<th scope="col">
							<button type="button" class="btn btn-primary" id="btnAddItem"><i class="bi bi-plus-circle"></i></button>
						</th>
					</tr>
				</thead>
				<tbody>
				</tbody>
				<tfoot>
					<tr class="table-active">
						<th colspan="3" class="item-total-label number-label">Total: </th>
						<th class="item-total number-label">0</th>
						<th>&nbsp;</th>
					</tr>
				</tfoot>
			</table>

			<button type="button" class="btn btn-light" id="btnClearInvoice">Clear</button>
			<button type="button" class="btn btn-primary" id="btnSaveInvoice">Save</button>
		</form>
	</div>
	
	<div class="col-md-3">
		<h3>Invoice List</h3>
		<hr />
		<table class="table table-sm table-hover" id="tblInvoiceList">
			<thead>
				<tr>
					<th scope="col">#</th>
					<th scope="col">Customer</th>
					<th scope="col">Date Entered</th>
					<th scope="col">&nbsp;</th>
				</tr>
			</thead>
			<tbody>
				<?php foreach($invoices as $invoice): ?>
					<tr class="invoice" data-id="<?php echo $invoice->ID; ?>">
						<td class="invoice-detail-number"><?php echo $invoice->number; ?></td>
						<td><?php echo $invoice->customer_name; ?></td>
						<td><?php echo date('Y-m-d', strtotime($invoice->date_created)); ?></td>
						<td>
							<i class="bi bi-trash btnRemoveInvoice"></i>
						</td>
					</tr>
				<?php endforeach; ?>
			</tbody>
		</table>
	</div>
</div>

<script type="text/javascript">

	var enteredBy = <?php echo $cuser->ID; ?>;
	var deletedItems = [];

	function addItemToList(item) {
		var itemNode = $('<tr class="item-detail" data-id="' + item.ID + '" data-product-id="' + item.productID + '" />');
		$("#tblItems tbody").append(itemNode);

		var productsNode = $('#ddlProducts').clone();
		productsNode.removeAttr('id')
		productsNode.val(item.productID);

		var productContainerNode = $('<td></td>');
		productContainerNode.append(productsNode);
		
		itemNode.append(productContainerNode);
		itemNode.append('<td class="number-label"><span class="item-detail-price">' + item.price.toFixed(2) +'</span></td>');
		itemNode.append('<td><input class="form-control txtItemQTY number-label" type="number" placeholder="QTY" min="1" max="9999" step="1" value="' + item.qty + '"></td>');
		itemNode.append('<td class="number-label"><span class="item-detail-total">' + item.price.toFixed(2) +'</span></td>');
		itemNode.append('<td><button type="button" class="btn btn-danger btnRemoveItem"><i class="bi bi-dash-circle"></i></button></td>');
		
		updateQtyMaxVal(itemNode);
		computeItemTotal(itemNode);
	}

	function computeItemTotal(itemNode) {

		var productPrice = itemNode.find('.item-detail-price').html();

		var qty = itemNode.find('.txtItemQTY').val();
		qty = parseFloat(qty);

		if (qty > 0) {
			var total = parseFloat(productPrice) * qty;
			itemNode.find('.item-detail-total').html(total.toFixed(2));
		}
		computeTotal();
	}

	function computeTotal() {
		
		var total = 0;
		$('.item-detail:not(.main-item-detail)').each(function() {
			var totalPrice = $(this).find('.item-detail-total').html();
			total += parseFloat(totalPrice);
		});
		$('.item-total').html(total.toFixed(2));
	}

	function updateQtyMaxVal(itemNode) {
		var newProductQty = itemNode.find('.ddlItemProducts option:selected').attr('data-qty');
		newProductQty = parseFloat(newProductQty);
		var qty = itemNode.find('.txtItemQTY').val();
		qty = parseFloat(qty);

		if (qty > newProductQty) {
			itemNode.find('.txtItemQTY').val(newProductQty);
		}

		itemNode.find('.txtItemQTY').attr('max', newProductQty);
	}

	function clearForm() {
		$('#hdnInvoiceID').val('');
		$('#txtInvoiceNumber').val('');
		$('.item-detail:not(.main-item-detail)').remove();
		$('.item-total').html(0);
		$('#btnClearInvoice').html('Clear');
		$('.top-label').html('Create Invoice');
		deletedItems.length = 0;
	}

	$('#btnAddItem').on("click", function(){

		var productID = $('#ddlProducts').val();
		var productName = $('#ddlProducts option:selected').html();
		var productPrice = $('#ddlProducts option:selected').attr('data-price');
		productPrice = parseFloat(productPrice);
		var qty = $('#txtQty').val();
		qty = parseInt(qty);
		
		var errors = [];
		if($('.item-detail[data-product-id=' + productID + ']').length > 0) {
			errors.push("Product already on item list");
		}
		if(!qty){
			errors.push("Please enter a QTY!");
		} else if (qty <= 0) {
			errors.push("Please enter a postive number for QTY");
		}

		if(errors.length > 0){
			alert(errors.join('\n'));
			return;
		}

		var item = {
			ID: 0,
			productID: productID,
			name: productName,
			price: productPrice,
			qty: qty
		};
		addItemToList(item);
	});
	
	$('#btnSaveInvoice').on("click", function(){
		
		var customerID = $('#ddlCustomers').val();
		var customerName = $('#ddlCustomers option:selected').html();
		var number = $('#txtInvoiceNumber').val();
		var invoiceID = $('#hdnInvoiceID').val();
		invoiceID = invoiceID.length == 0 ? 0 : parseInt(invoiceID);
		var invoiceTotal = $('.item-total').html();
		invoiceTotal = invoiceTotal;
		
		if(number.length == 0){
			alert("Please enter an invoice number!");
			return;
		}

		var itemList = [];
		$('.item-detail:not(.main-item-detail)').each(function() {
			var totalPrice = $(this).find('.item-detail-total').html();
			itemList.push({
				ID: $(this).attr('data-id'),
				product_ID: $(this).find('.ddlItemProducts').val(),
				qty: $(this).find('.txtItemQTY').val(),
				invoice_price: $(this).find('.ddlItemProducts option:selected').attr('data-price')
			});
		});
		
		if(itemList.length == 0){
			alert("Please enter atleast 1 item!");
			return;
		}

		if (invoiceID) {
			for(i in deletedItems) {
				itemList.push({
					ID: deletedItems[i],
					product_ID: -1 // NOTE: use this to remove from BE
				});
			}
		}
		
		showLoader();
		$.ajax({
			url: BASE_URL + 'invoice/' + (invoiceID ? invoiceID : ''),
			type: invoiceID ? 'put' : 'post',
			data: { 
				number: number,
				customer_ID: customerID,
				employee_ID: enteredBy,
				total: invoiceTotal
			},
			success: function(invoice){
				
				for(i in itemList) {
					itemList[i].invoice_ID = invoice.ID;
				}
				$.ajax({
					url: invoiceID ? BASE_URL + 'invoice_item/batch_update' :  BASE_URL + 'invoice_item/batch_save',
					type: "post",
					data: { item_list: itemList },
					success: function(invoiceItemList){
						invoice.items = invoiceItemList;

						if (!invoiceID) {
							var invoiceNode = $('<tr class="invoice" data-id="' + invoice.ID + '"/>');
							$("#tblInvoiceList tbody").append(invoiceNode);
							
							invoiceNode.append('<td>' + number +'</td>');
							invoiceNode.append('<td>' + customerName +'</td>');
							invoiceNode.append('<td>Just Now</td>');
						}
						
						clearForm();
						loadInvoice(invoice);
						hideLoader();
						alert('Invoice Successfully ' + (invoiceID ? 'Updated!' : 'Saved!'));
					},
					error: function(error){
						
						hideLoader();
						alert(error.responseText);
					}
				});
			},
			error: function(error){
				
				hideLoader();
				alert(error.responseText);
			}
		});
	});

	$('#btnClearInvoice').on("click", function(){
		clearForm();
	});
	
	$(document).on("change", ".ddlItemProducts", function(){
		var itemNode = $(this).parents('.item-detail');

		var currentProductID = itemNode.attr('data-product-id');
		var newProductID = $(this).val();

		if (currentProductID) { // NOTE: check if not main item form
			if($('.item-detail[data-product-id=' + newProductID + ']:not(.main-item-detail)').length > 0) {
				alert("Product already on item list");
				$(this).val(currentProductID);
				return;
			}
			itemNode.attr('data-product-id', newProductID);
		}
		
		// NOTE: limit qty based on SOH
		updateQtyMaxVal(itemNode);
		
		var productPrice = itemNode.find('.ddlItemProducts option:selected').attr('data-price');
		var productPriceD = parseFloat(productPrice);
		itemNode.find('.item-detail-price').html(productPriceD.toFixed(2));

		computeItemTotal(itemNode);
	});
	
	$(document).on("change", ".txtItemQTY", function(){

		var max = $(this).attr('max');
		max = parseInt(max);

		var currentVal = $(this).val();
		currentVal = parseInt(currentVal);

		if (currentVal > max) {
			currentVal = max;
		} else if (currentVal <= 0) {
			currentVal = 1;
		}
		$(this).val(currentVal);

		var itemNode = $(this).parents('.item-detail');
		computeItemTotal(itemNode);
	});
	
	$(document).on("click", ".btnRemoveItem", function(){
		var itemNode = $(this).parents('.item-detail');
		itemNode.remove();

		var itemID = itemNode.attr('data-id');
		itemID = parseInt(itemID);
		if (itemID > 0) {
			deletedItems.push(itemID);
		}

		computeItemTotal(itemNode);
	});
	
	$(document).on("click", ".invoice", function(){
		var invoiceID = $(this).attr('data-id');
		var number = $(this).find('.invoice-detail-number').html();

		if (confirm('Would you like to load Invoice#"' + number + '"?')) {
			showLoader();
			$.ajax({
				url: BASE_URL + 'invoice/' + invoiceID,
				type: "get",
				success: function(invoice){
					clearForm();
					loadInvoice(invoice);
					hideLoader();
				},
				error: function(error){
					
					hideLoader();
					alert(error.responseText);
				}
			});
		}
	});
	
	$(document).on("click", ".btnRemoveInvoice", function(evt){
		evt.stopPropagation();

		var invoiceNode = $(this).parents('.invoice');
		var invoiceID = invoiceNode.attr('data-id');
		var number = invoiceNode.find('.invoice-detail-number').html();

		if (confirm('Would you like to remove Invoice#' + number + '?')) {
			showLoader();
			$.ajax({
				url: BASE_URL + 'invoice/' + invoiceID,
				type: "delete",
				success: function(invoice){
					invoiceNode.remove();
					hideLoader();
					alert('Invoice#' + number + ' successfully deleted!');
				},
				error: function(error){
					
					hideLoader();
					alert(error.responseText);
				}
			});
		}
	});

	function loadInvoice(invoice) {
		$('#hdnInvoiceID').val(invoice.ID);
		$('#ddlCustomers').val(invoice.customer_ID);
		$('#txtInvoiceNumber').val(invoice.number);
		$('#btnClearInvoice').html('Create New');
		$('.top-label').html('Update Invoice#' + invoice.number);

		for(i in invoice.items) {
			var productName = $('#ddlProducts option[value=' + invoice.items[i].product_ID + ']').html();
			var item = {
				ID: invoice.items[i].ID,
				productID: invoice.items[i].product_ID,
				name: productName,
				price: parseFloat(invoice.items[i].invoice_price),
				qty: parseInt(invoice.items[i].qty)
			};
			addItemToList(item);
		}
	}
	
	$(document).ready(function(){
		$('#ddlProducts').change();
	});

</script>
