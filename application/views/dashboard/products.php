<h2>Products</h2>

<table class="table table-striped" id="tblProducts">
  <thead>
    <tr>
      <th scope="col">#</th>
      <th scope="col">Name</th>
      <th scope="col">Price</th>
      <th scope="col">QTY</th>
      <th scope="col"></th>
    </tr>
    <tr>
      <th scope="col"></th>
      <th scope="col">
	  	<input class="form-control" type="text" placeholder="Name" maxlength="255" id="txtName">
	  </th>
      <th scope="col">
	  	<input class="form-control" type="number" placeholder="Price" min="1" max="9999" step="1" id="txtPrice">
	  </th>
      <th scope="col">
	  	<input class="form-control" type="number" placeholder="QTY" min="1" max="9999" step="1" id="txtQty">
	  </th>
      <th scope="col">
	  	<button type="button" class="btn btn-primary" id="btnAddProduct">Add New</button>
	  </th>
    </tr>
  </thead>
  <tbody>
  </tbody>
</table>

<script type="text/javascript">

	function loadProducts() {

		showLoader();
		$.ajax({
			url: BASE_URL + 'product/',
			type: "get",
			success: function(products){
				
				for(i in products){
					addProductToList(products[i]);
				}
				hideLoader();
			},
			error: function(error){
				
				hideLoader();
				alert(error.responseText);
			}
		});
	}

	function addProductToList(product) {
		var detailsNode = $('<tr class="product-detail" />');
		$("#tblProducts tbody").append(detailsNode);
		
		detailsNode.append('<td><span class="product-detail-id">' + product.ID +'</span></td>');
		detailsNode.append('<td><span class="product-detail-name">' + product.name +'</span></td>');
		detailsNode.append('<td><span class="product-detail-price">' + product.price +'</span></td>');
		detailsNode.append('<td><span class="product-detail-qty">' + product.qty +'</span></td>');
		detailsNode.append('<td><button type="button" class="btn btn-secondary btnEditProduct"><i class="bi bi-pencil"></i></button><button type="button" class="btn btn-danger btnRemoveProduct"><i class="bi bi-dash-circle"></i></button></td>');
	}
	
	$("#btnAddProduct").on("click", function(){

		var name = $('#txtName').val();
		var price = $('#txtPrice').val();
		var qty = $('#txtQty').val();

		var errors = [];
		if(name.length == 0){
			errors.push("Please enter a name!");
		}

		if(price.length == 0){
			errors.push("Please enter a price!");
		} else if (price <= 0) {
			errors.push("Please enter a postive number for price");
		}

		if(qty.length == 0){
			errors.push("Please enter a QTY!");
		} else if (qty <= 0) {
			errors.push("Please enter a postive number for QTY");
		}

		if(errors.length > 0){
			alert(errors.join('\n'));
			return;
		}
		
		showLoader();
		$.ajax({
			url: BASE_URL + 'product/',
			type: "post",
			data: { 
				name: name,
				price: price,
				qty: qty
			},
			success: function(product){
				addProductToList(product);
				hideLoader();
			},
			error: function(error){
				
				hideLoader();
				alert(error.responseText);
			}
		});
	});
	
	$(document).on("click", ".btnEditProduct", function(){
		var detailsNode = $(this).parents(".product-detail");
		$(this).addClass("btnUpdateProduct").removeClass("btnEditProduct");
		$(this).html('<i class="bi bi-save"></i>');
		
		var nameNode = detailsNode.find(".product-detail-name");
		nameNode.after('<input type="text" class="product-detail-name-edit" value="' + nameNode.html() + '" />').remove();
		
		var valueNode = detailsNode.find(".product-detail-price");
		valueNode.after('<input type="number" class="product-detail-price-edit" value="' + valueNode.html() + '" />').remove();
		
		var qtyNode = detailsNode.find(".product-detail-qty");
		qtyNode.after('<input type="number" class="product-detail-qty-edit" value="' + qtyNode.html() + '" />').remove();
	});
	
	$(document).on("click", ".btnUpdateProduct", function(){
		var detailsNode = $(this).parents(".product-detail");
		$(this).addClass("btnEditProduct").removeClass("btnUpdateProduct");
		$(this).html('<i class="bi bi-pencil"></i>');

		var idNode = detailsNode.find(".product-detail-id");
		var id = idNode.html();
		
		var nameNode = detailsNode.find(".product-detail-name-edit");
		var name = nameNode.val();
		
		var valueNode = detailsNode.find(".product-detail-price-edit");
		var price = valueNode.val();
		
		var qtyNode = detailsNode.find(".product-detail-qty-edit");
		var qty = qtyNode.val();

		var errors = [];
		if(name.length == 0){
			errors.push("Please enter a name!");
		}

		if(price.length == 0){
			errors.push("Please enter a price!");
		} else if (price <= 0) {
			errors.push("Please enter a postive number for price");
		}

		if(qty.length == 0){
			errors.push("Please enter a QTY!");
		} else if (qty <= 0) {
			errors.push("Please enter a postive number for QTY");
		}

		if(errors.length > 0){
			alert(errors.join('\n'));
			return;
		}
		
		showLoader();
		$.ajax({
			url: BASE_URL + 'product/' + id,
			type: "put",
			data: { 
				name: name,
				price: price,
				qty: qty
			},
			success: function(product){
				nameNode.after('<span class="product-detail-name">' + name +'</span>').remove();
				valueNode.after('<span class="product-detail-price">' + price +'</span>').remove();
				qtyNode.after('<span class="product-detail-qty">' + qty +'</span>').remove();
				hideLoader();
			},
			error: function(error){
				
				hideLoader();
				alert(error.responseText);
			}
		});
	});
	
	$(document).on("click", ".btnRemoveProduct", function(){
		var detailsNode = $(this).parents(".product-detail");

		var idNode = detailsNode.find(".product-detail-id");
		var id = idNode.html();

		var nameNode = detailsNode.find(".product-detail-name");
		var name = nameNode.html();

		if (confirm('Are you sure you want to remove "' + name + '"?')) {
			
			showLoader();
			$.ajax({
				url: BASE_URL + 'product/' + id,
				type: "delete",
				success: function(product){
					hideLoader();
					detailsNode.remove();
					alert('Successfully removed "' + name + '"!');
				},
				error: function(error){
					hideLoader();
					alert(error.responseText);
				}
			});
		}
	});
	
	$(document).ready(function(){
		
		loadProducts();
	});

</script>
