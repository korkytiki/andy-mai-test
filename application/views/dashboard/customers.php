<h2>Customers</h2>

<table class="table table-striped" id="tblCustomers">
  <thead>
    <tr>
      <th scope="col">#</th>
      <th scope="col">Name</th>
      <th scope="col" style="width: 120px;"></th>
    </tr>
    <tr>
      <th scope="col"></th>
      <th scope="col">
	  	<input class="form-control" type="text" placeholder="Name" maxlength="255" id="txtName">
	  </th>
      <th scope="col">
	  	<button type="button" class="btn btn-primary" id="btnAddCustomer">Add New</button>
	  </th>
    </tr>
  </thead>
  <tbody>
  </tbody>
</table>

<script type="text/javascript">

	function loadCustomers() {

		showLoader();
		$.ajax({
			url: BASE_URL + 'customer/',
			type: "get",
			success: function(customers){
				
				for(i in customers){
					addCustomerToList(customers[i]);
				}
				hideLoader();
			},
			error: function(error){
				
				hideLoader();
				alert(error.responseText);
			}
		});
	}

	function addCustomerToList(customer) {
		var detailsNode = $('<tr class="customer-detail" />');
		$("#tblCustomers tbody").append(detailsNode);
		
		detailsNode.append('<td><span class="customer-detail-id">' + customer.ID +'</span></td>');
		detailsNode.append('<td><span class="customer-detail-name">' + customer.name +'</span></td>');
		detailsNode.append('<td><button type="button" class="btn btn-secondary btnEditCustomer"><i class="bi bi-pencil"></i></button><button type="button" class="btn btn-danger btnRemoveCustomer"><i class="bi bi-dash-circle"></i></button></td>');
	}
	
	$("#btnAddCustomer").on("click", function(){

		var name = $('#txtName').val();

		var errors = [];
		if(name.length == 0){
			errors.push("Please enter a name!");
		}

		if(errors.length > 0){
			alert(errors.join('\n'));
			return;
		}
		
		showLoader();
		$.ajax({
			url: BASE_URL + 'customer/',
			type: "post",
			data: { 
				name: name
			},
			success: function(customer){
				addCustomerToList(customer);
				hideLoader();
			},
			error: function(error){
				
				hideLoader();
				alert(error.responseText);
			}
		});
	});
	
	$(document).on("click", ".btnEditCustomer", function(){
		var detailsNode = $(this).parents(".customer-detail");
		$(this).addClass("btnUpdateCustomer").removeClass("btnEditCustomer");
		$(this).html('<i class="bi bi-save"></i>');
		
		var nameNode = detailsNode.find(".customer-detail-name");
		nameNode.after('<input type="text" class="customer-detail-name-edit" value="' + nameNode.html() + '" />').remove();
	});
	
	$(document).on("click", ".btnUpdateCustomer", function(){
		var detailsNode = $(this).parents(".customer-detail");
		$(this).addClass("btnEditCustomer").removeClass("btnUpdateCustomer");
		$(this).html('<i class="bi bi-pencil"></i>');

		var idNode = detailsNode.find(".customer-detail-id");
		var id = idNode.html();
		
		var nameNode = detailsNode.find(".customer-detail-name-edit");
		var name = nameNode.val();

		var errors = [];
		if(name.length == 0){
			errors.push("Please enter a name!");
		}

		if(errors.length > 0){
			alert(errors.join('\n'));
			return;
		}
		
		showLoader();
		$.ajax({
			url: BASE_URL + 'customer/' + id,
			type: "put",
			data: { 
				name: name
			},
			success: function(customer){
				nameNode.after('<span class="customer-detail-name">' + name +'</span>').remove();
				hideLoader();
			},
			error: function(error){
				
				hideLoader();
				alert(error.responseText);
			}
		});
	});
	
	$(document).on("click", ".btnRemoveCustomer", function(){
		var detailsNode = $(this).parents(".customer-detail");

		var idNode = detailsNode.find(".customer-detail-id");
		var id = idNode.html();

		var nameNode = detailsNode.find(".customer-detail-name");
		var name = nameNode.html();

		if (confirm('Are you sure you want to remove "' + name + '"?')) {
			
			showLoader();
			$.ajax({
				url: BASE_URL + 'customer/' + id,
				type: "delete",
				success: function(customer){
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
		
		loadCustomers();
	});

</script>
