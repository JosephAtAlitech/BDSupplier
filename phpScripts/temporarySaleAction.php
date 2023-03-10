<?php
$conPrefix = '../';
include $conPrefix . 'includes/session.php';

date_default_timezone_set('Asia/Dhaka');
//$toDay = (new DateTime($test))->format("Y-m-d H:i:s");
$toDay = "2022-08-21 07:21:53";
$toDate = (new DateTime())->format("Y-m-d");
//session_start();
if (isset($_POST["action"])) {
	if ($_POST["action"] == "add") {
		$totalStock = 0;
		$stockError = 0;
		$currentStockId = '';
		$productId = $_POST["product_id"];
		$warehouseId = $_POST["warehouse_id"];
		$serializeProductsId = $_POST["serializeProductsId"];
		$serializeSaleQuantity = $_POST["serializeSaleQuantity"];
		$product_quantity = $_POST["product_quantity"];
		$productType = '';
		/* $sql = "SELECT currentStock AS totalStock, id 
                FROM tbl_currentStock
                WHERE tbl_productsId='$productId' AND tbl_wareHouseId='$warehouseId'"; */
		//updated For serialize products
		$sql = "SELECT tbl_currentStock.currentStock AS totalStock, tbl_currentStock.id, tbl_products.type 
				FROM tbl_currentStock
				INNER JOIN tbl_products ON tbl_currentStock.tbl_productsId=tbl_products.id
				WHERE tbl_productsId='$productId' AND tbl_currentStock.tbl_wareHouseId='$warehouseId' AND tbl_currentStock.deleted='No'";
		$result = $conn->query($sql);
		if ($result) {
			while ($row = $result->fetch_assoc()) {
				$productType = $row['type'];
				$totalStock += $row['totalStock'];
				$currentStockId = $row['id'];
			}
			if ($totalStock == '') {
				$totalStock = 0;
			}
		} else {
			$totalStock = 0;
			$currentStockId = rand();
		}
		if ($totalStock > 0) {
			$serializeIdArray = array();
			$serializeSaleQtyArray = array();
			$is_productSame = 0;
			if (isset($_SESSION["temporarySaleShopping_cart"])) {
				$is_available = 0;
				foreach ($_SESSION["temporarySaleShopping_cart"] as $keys => $values) {
					if ($_SESSION["temporarySaleShopping_cart"][$keys]['product_id'] == $_POST["product_id"] && $_SESSION["temporarySaleShopping_cart"][$keys]['warehouse_id'] == $_POST["warehouse_id"]) {
						$is_available++;
						$is_productSame = 0;
						if ($totalStock >= $_SESSION["temporarySaleShopping_cart"][$keys]['product_quantity'] + $_POST["product_quantity"]) {
							$_SESSION["temporarySaleShopping_cart"][$keys]['product_quantity'] = $_SESSION["temporarySaleShopping_cart"][$keys]['product_quantity'] + $_POST["product_quantity"];
							$lastValue = substr($_SESSION["temporarySaleShopping_cart"][$keys]['product_discount'], -1);
							if ($lastValue != "%") {
								$_SESSION["temporarySaleShopping_cart"][$keys]['product_discount'] = $_SESSION["temporarySaleShopping_cart"][$keys]['product_discount'] + $_POST["product_discount"];
							}
							//$_SESSION["temporarySaleShopping_cart"][$keys]['product_discount'] = $_SESSION["temporarySaleShopping_cart"][$keys]['product_discount'] + $_POST["product_discount"];
						} else {
							$stockError++;
						}
						break;
					} else if ($_SESSION["temporarySaleShopping_cart"][$keys]['product_id'] == $_POST["product_id"]) {
						$is_productSame = 1;
					}
				}
				if ($is_available == 0) {
					if ($productType == "serialize") {
						$product_quantity  = $serializeSaleQuantity;
					}
					$discount_percent = (($_POST["max_price"] - $_POST["min_price"]) / $_POST["max_price"]) * 100;
					$item_array = array(
						'id'                       =>     $currentStockId,
						'product_id'               =>     $_POST["product_id"],
						'product_name'             =>     $_POST["product_name"],
						'product_price'            =>     $_POST["max_price"],
						'min_price'            	   =>     $_POST["min_price"],
						'max_price'                =>     $_POST["max_price"],
						'product_quantity'         =>     $product_quantity,
						'warehouse_id'             =>     $_POST["warehouse_id"],
						'warehouse_name'           =>     $_POST["warehouse_name"],
						'product_limit'            =>     $totalStock,
						'product_discount'         =>     $discount_percent . '%',
						'status'                   =>     $is_productSame,
						'product_type'        	   =>     $productType,
						'serializeIdArray'  	   =>  [$serializeProductsId],
						'serializeSaleQtyArray'    =>  [$serializeSaleQuantity]
					);
					$_SESSION["temporarySaleShopping_cart"][] = $item_array;
				}
			} else {
				if ($productType == "serialize") {
					$product_quantity  = $serializeSaleQuantity;
				}
				$discount_percent = (($_POST["max_price"] - $_POST["min_price"]) / $_POST["max_price"]) * 100;
				$item_array = array(
					'id'                       =>     $currentStockId,
					'product_id'               =>     $_POST["product_id"],
					'product_name'             =>     $_POST["product_name"],
					'product_price'            =>     $_POST["max_price"],
					'min_price'            	   =>     $_POST["min_price"],
					'max_price'                =>     $_POST["max_price"],
					'product_quantity'         =>     $product_quantity,
					'warehouse_id'             =>     $_POST["warehouse_id"],
					'warehouse_name'           =>     $_POST["warehouse_name"],
					'product_limit'            =>     $totalStock,
					'product_discount'         =>     $discount_percent . '%',
					'status'                   =>     $is_productSame,
					'product_type'        	   =>     $productType,
					'serializeIdArray'  	   =>  [$serializeProductsId],
					'serializeSaleQtyArray'    =>  [$serializeSaleQuantity]
				);
				$_SESSION["temporarySaleShopping_cart"][] = $item_array;
			}
		} else {
			$stockError++;
		}
		// echo $stockError;
		$data = array('productType' => $productType, 'productId' => $_POST["product_id"], 'warehouseId' => $_POST["warehouse_id"], 'count' => $stockError, 'currentStockId' => $currentStockId);
		echo json_encode($data);
	} else if ($_POST["action"] == 'remove') {
		$changed = 0;
		$productId = 0;
		foreach ($_SESSION["temporarySaleShopping_cart"] as $keys => $values) {
			if ($values["id"] == $_POST["id"] && $changed == 0) {
				$productId = $values["product_id"];
				unset($_SESSION["temporarySaleShopping_cart"][$keys]);
				$changed = 1;
				//break;
			} else if ($values["product_id"] == $productId) {
				$_SESSION["temporarySaleShopping_cart"][$keys]["status"] = $_SESSION["temporarySaleShopping_cart"][$keys]["status"] - 1;
			}
		}
	} else if ($_POST["action"] == "adjust") {
		$productType = '';
		$productId = '';
		$warehouseId = '';
		$currentStockId = '';
		if (isset($_SESSION["temporarySaleShopping_cart"])) {
			foreach ($_SESSION["temporarySaleShopping_cart"] as $keys => $values) {
				if ($_SESSION["temporarySaleShopping_cart"][$keys]['id'] == $_POST["id"]) {
					$_SESSION["temporarySaleShopping_cart"][$keys]['product_quantity'] =  $_POST["product_quantity"];
					$_SESSION["temporarySaleShopping_cart"][$keys]['product_price'] =  $_POST["product_price"];
					$_SESSION["temporarySaleShopping_cart"][$keys]['product_limit'] =  $_POST["product_limit"];
					$_SESSION["temporarySaleShopping_cart"][$keys]['product_discount'] =  $_POST["product_discount"];
					// Serialize Product
					if ($_SESSION["temporarySaleShopping_cart"][$keys]['product_type'] == "serialize") {
						if ($_POST['product_type']) {
							$serializeId = $_POST['serializeProductsId'];
							$serializeSaleQty = $_POST['serializeSaleQuantity'];
							$serializeIdExist = TRUE;
							foreach ($_SESSION["temporarySaleShopping_cart"][$keys]['serializeIdArray'] as $key => $value) {
								if ($value == $serializeId) {
									//session()->put("sale_cart_array." . $keys . ".serializeSaleQtyArray." . $key, $serializeSaleQty);
									$_SESSION["temporarySaleShopping_cart"][$keys]['serializeSaleQtyArray'][$key] =  $serializeSaleQty;
									$ddd = $_SESSION["temporarySaleShopping_cart"][$keys]['serializeSaleQtyArray'];
									$serializeIdExist = FALSE;
								}
							}
							if ($serializeIdExist) {
								/*Session::push("sale_cart_array." . $keys . ".serializeIdArray", $serializeId);
								Session::push("sale_cart_array." . $keys . ".serializeSaleQtyArray", $serializeSaleQty);*/
								array_push($_SESSION["temporarySaleShopping_cart"][$keys]['serializeIdArray'], $serializeId);

								array_push($_SESSION["temporarySaleShopping_cart"][$keys]['serializeSaleQtyArray'], $serializeSaleQty);
								$ddd = $_SESSION["temporarySaleShopping_cart"][$keys]['serializeSaleQtyArray'];
							}
						}
					}
					$productType = $_SESSION["temporarySaleShopping_cart"][$keys]['product_type'];
					$productId = $_SESSION["temporarySaleShopping_cart"][$keys]['product_id'];
					$warehouseId = $_SESSION["temporarySaleShopping_cart"][$keys]['warehouse_id'];
					$currentStockId = $_SESSION["temporarySaleShopping_cart"][$keys]['id'];
					// End Serialize Product
					break;
				}
			}
		}
		//echo $_POST["product_quantity"];
		$data = array('productType' => $productType, 'productId' => $productId, 'warehouseId' => $warehouseId, 'currentStockId' => $currentStockId, 'ddd' => $ddd);
		echo json_encode($data);
	} else if ($_POST["action"] == 'empty') {
		unset($_SESSION["temporarySaleShopping_cart"]);
	} else if ($_POST["action"] == 'FinalSalesConfirmation') {
		$error = 0;
		$checkOverQuantity = 0;
		$loginID = $_SESSION['user'];
		$sales = explode(",", $_POST['sales']);
		$salesProduct = explode(",", $_POST['salesProduct']);
		$vouchers = explode(",", $_POST['vouchers']);
		$rowIds = $_POST['rowId'];
		$updateWarehouses = $_POST['updateWarehouse'];
		$rowIdArray = explode(",", $rowIds);
		$updateWarehouseArray = explode(",", $updateWarehouses);
		$ind = 14;
		for ($i = 0; $i < count($rowIdArray); $i++) {
			if ($rowIdArray[$i] != "") {
				$salesProduct[($rowIdArray[$i] * $ind) + 9] = $updateWarehouseArray[$i];
			}
		}

		//$noofSaleProducts = count($salesProduct)/$ind;
		$wirehouseProduct = array(array());

		for ($i = 0; $i < count($salesProduct); $i = $i + $ind) {
			$updateWarehouseFlag = 0;
			for ($j = 0; $j < count($wirehouseProduct); $j++) {

				if ($wirehouseProduct[$j][0] == $salesProduct[$i] && $wirehouseProduct[$j][1] == $salesProduct[$i + 9]) {
					$wirehouseProduct[$j][2] = $wirehouseProduct[$j][2] + $salesProduct[$i + 1];
					$updateWarehouseFlag = 1;
				}
			}
			if ($i == 0) {
				$j = 0;
			}

			if ($updateWarehouseFlag == 0) {
				$wirehouseProduct[$j][0] = $salesProduct[$i];
				$wirehouseProduct[$j][1] = $salesProduct[$i + 9];
				$wirehouseProduct[$j][2] = $salesProduct[$i + 1];
			}
		}

		for ($i = 0; $i < count($wirehouseProduct); $i++) {
			$productIdEntry = $wirehouseProduct[$i][0];
			$warehouse_idEntry = $wirehouseProduct[$i][1];
			$productQuantityEntry = $wirehouseProduct[$i][2];
			$sql = "SELECT SUM(currentStock) AS totalStock 
                    FROM tbl_currentStock
                    WHERE tbl_productsId='$productIdEntry' AND tbl_warehouseId='$warehouse_idEntry'";
			$result = $conn->query($sql);
			while ($row = $result->fetch_assoc()) {
				$totalStock = $row['totalStock'];
				if ($totalStock < $productQuantityEntry) {
					$checkOverQuantity++;
				}
			}
		}
		//$checkOverQuantity = 1;
		if ($checkOverQuantity == 0) {
			$sql = "SELECT LPAD(max(tsNo)+1, 6, 0) as salesCode from tbl_temporary_sale";
			$query = $conn->query($sql);
			while ($prow = $query->fetch_assoc()) {
				$salesOrderNo = $prow['salesCode'];
			}
			if ($salesOrderNo == "") {
				$salesOrderNo = "000001";
			}
			$grandTotalAfterOffer = 0;
			$sql = "INSERT INTO tbl_temporary_sale (tsNo, tSalesDate, tbl_customerId, paymentType, inv_remarks, remarks, createdBy, tbl_userId, requisitionNo, tbl_wareHouseId, referenceInfo, createdDate)
        	        VALUES ('$salesOrderNo','$sales[0]','$sales[1]','$sales[11]','$sales[12]','Temporary Sales with salesCode: .$salesOrderNo','$loginID','$sales[2]','$sales[14]','','$sales[16]','$toDay')";

			/*$sql = "INSERT INTO tbl_sales (salesOrderNo, salesDate, tbl_customerId, tbl_userId, totalAmount, productDiscount, salesDiscount, totalDiscount, grandTotal, vat, ait, createdBy, type, paymentType, remarks, tbl_wareHouseId, carringCost, requisitionNo, tbl_transport_info, projectName, previousDue, paidAmount, totalDue, createdDate) 
        	        VALUES ('$salesOrderNo','$sales[0]','$sales[1]','$sales[2]','$sales[3]','$sales[4]','$sales[5]','$sales[6]','$grandTotalAfterOffer','$sales[8]','$sales[9]','$loginID','$sales[10]',
        	        '$sales[11]','$sales[12]','$sales[13]','$sales[13]','$sales[14]','$sales[15]','$sales[16]','$previousDue', '$sales[17]', '$totalDue', '$toDay')";*/
			//echo json_encode($sql);
			if ($conn->query($sql)) {
				$salesId = $conn->insert_id;
				for ($i = 0; $i < count($salesProduct); $i = $i + $ind) {
					//$ind = $i + 1;
					if ($salesProduct[$i] != "") {
						$productIdEntry = $salesProduct[$i];
						$qty = $salesProduct[$i + 1];
						$productPriceEntry = $salesProduct[$i + 3];
						$productTotal = $salesProduct[$i + 4];
						$discountOfferValue = $salesProduct[$i + 5];
						$grandTotal = $salesProduct[$i + 6];
						$productDiscount = $salesProduct[$i + 7];
						$discountId = $salesProduct[$i + 8];
						$warehouse_idEntry = $salesProduct[$i + 9];
						$amount = $salesProduct[$i + 13];
						$sql = "INSERT INTO tbl_tsalesproducts (tbl_tSalesId, tbl_productsId, quantity, units, createdBy, saleAmount, amount, totalAmount, discount, grandTotal, remarks, tbl_wareHouseId,createdDate,tbl_discount_offer_id) 
                            		    VALUES ('$salesId','$productIdEntry','$qty','','$loginID','$productPriceEntry','$amount','$productTotal','$discountOfferValue','$productTotal','$productDiscount','$warehouse_idEntry','$toDay','$discountId')";
						/*$sql = "INSERT INTO tbl_sales_products (tbl_salesId, tbl_productsId, quantity, units, createdBy, salesAmount, totalAmount, discount, grandTotal, remarks, tbl_wareHouseId,createdDate,tbl_discount_offer_id) 
        						        VALUES ($salesId,$productIdEntry,$qty,'','$loginID','$productPriceEntry','$productTotal','$discountOfferValue','$grandTotal',
        						        '$productDiscount','$warehouse_idEntry','$toDay','$discountId')";*/
						$conn->query($sql);
						$sql = "UPDATE tbl_currentStock 
        								    set salesStock=salesStock+$qty, currentStock=currentStock-$qty, lastUpdatedDate='$toDay', lastUpdatedBy='$loginID'
        								    where tbl_productsId = '$productIdEntry' AND tbl_wareHouseId='$warehouse_idEntry' AND deleted='No'";
						$query1 = $conn->query($sql);
						if ($conn->affected_rows == 0) {
							$sql = "insert into tbl_currentStock 
        							            (salesStock, currentStock, tbl_productsId, tbl_wareHouseId, entryBy,entryDate) values 
        							            ('$qty', '-$qty','$productIdEntry','$warehouse_idEntry','$loginID','$toDay')";
							$query2 = $conn->query($sql);
						}
						if ($query1 || $query2) {
							$sql = "UPDATE tbl_products 
                                            SET saleTime=saleTime+1
                                            WHERE id='$productIdEntry' AND deleted='No' AND status='Active'";
							$conn->query($sql);
						}
					}
				}
				// Start Insert Sale Serialize Product
				foreach ($_SESSION["temporarySaleShopping_cart"] as $keys => $values) {
					if ($_SESSION["temporarySaleShopping_cart"][$keys]["product_type"] == "serialize") {
						$product_id = $_SESSION["temporarySaleShopping_cart"][$keys]['product_id'];
						$warehouse_id = $_SESSION["temporarySaleShopping_cart"][$keys]['warehouse_id'];
						foreach ($_SESSION["temporarySaleShopping_cart"][$keys]["serializeIdArray"] as $key => $serializeId) {
							$serializeSaleQtyArray = $_SESSION["temporarySaleShopping_cart"][$keys]["serializeSaleQtyArray"];
							$sale_quantity = $serializeSaleQtyArray[$key];
							if (empty($serializeId) || empty($serializeSaleQtyArray[$key])) {
								continue;
							}
							$sql_saleSerializeProduct = "INSERT INTO `sale_serialize_products`(`tbl_temporary_sale_id`, `product_id`, `warehouse_id`, `tbl_serialize_products_id`, `sale_quantity`, `created_by`, `created_date`) 
        						        VALUES ($salesId,$product_id,$warehouse_id,'$serializeId','$sale_quantity','$loginID','$toDay')";
							$conn->query($sql_saleSerializeProduct);
						}
					}
				}
				// End Insert sale Serialize Product fsdf
				//Start Update Serialize Product fdsf
				foreach ($_SESSION["temporarySaleShopping_cart"] as $keys => $values) {
					if ($_SESSION["temporarySaleShopping_cart"][$keys]["product_type"] == "serialize") {
						$quantity = 0;
						foreach ($_SESSION["temporarySaleShopping_cart"][$keys]["serializeIdArray"] as $key => $serializeId) {
							$serializeSaleQtyArray = $_SESSION["temporarySaleShopping_cart"][$keys]["serializeSaleQtyArray"];
							if (empty($serializeId)) {
								continue;
							}
							$sql_serializeProduct = "select * from tbl_serialize_products where id='$serializeId'";
							$result_serializeProduct = $conn->query($sql_serializeProduct);
							if ($result_serializeProduct->num_rows > 0) {
								while ($row_serializeProduct = $result_serializeProduct->fetch_assoc()) {
									$totalSerializeQuantity = ($row_serializeProduct['used_quantity'] + $serializeSaleQtyArray[$key]);
									$update_serialize = "Update tbl_serialize_products set used_quantity='$totalSerializeQuantity'";
									if ($row_serializeProduct['quantity'] == $totalSerializeQuantity) {
										$update_serialize .= ", is_sold='OFF'";
									}
									$update_serialize .= " WHERE id='$serializeId'";
									$conn->query($update_serialize);
								}
							}
						}
					}
				}
				// End Update Serialize Product
				if ($error == 0) {
					/*$customerType = 'Party';
        				    $sql = "SELECT LPAD(IFNULL(max(voucherNo),0)+1, 6, 0) as voucherCode, LPAD(IFNULL(max(voucherNo),0)+2, 6, 0) as voucherReceiveCode 
        				            FROM tbl_paymentVoucher 
        				            WHERE tbl_partyId='$customers' AND customerType = '$customerType'";
                    		$query = $conn->query($sql);
                    		while ($prow = $query->fetch_assoc()) {
                    			$voucherNo = $prow['voucherCode'];
                    			$voucherReceiveNo = $prow['voucherReceiveCode'];
                    		}
                    		if($voucherNo == ""){
                    		    $voucherNo = "000001";
                    		    $voucherReceiveNo = "000002";
                    		}
                    		if ($grandTotalAfterOffer > 0){
            					$customers = $vouchers[0];
            					$grandTotalAfterOffer = $vouchers[1];
            					$paymentMethod = $vouchers[2];
            					$payable = $vouchers[3];
            					$type = $vouchers[4];
            					$sql = "INSERT INTO tbl_paymentVoucher (tbl_partyId, tbl_sales_id, amount, entryBy, paymentMethod, paymentDate, status, remarks, type, voucherType, voucherNo, customerType,entryDate) 
            							VALUES ('$customers', '$salesId', '$grandTotalAfterOffer', '$loginID', '$paymentMethod', '$salesDate', 'Active', 'Payable for Party Sales Code: $salesOrderNo', 'partyPayable', 'PartySale', '$voucherNo', '$customerType','$toDay')";
            					$conn->query($sql);
            				}else{
            				    $grandTotal = 0;
            				}
        				    if ($vouchers[5] != ""){
            					
            					$customers = $vouchers[5];
            					$paidAmount = $vouchers[6];
            					$paymentMethod = $vouchers[7];
            					$payable = $vouchers[8];
            					$type = $vouchers[9];
            					$sql = "INSERT INTO tbl_paymentVoucher (tbl_partyId, tbl_sales_id, amount, entryBy, paymentMethod, paymentDate, status, remarks, type, voucherType, voucherNo, customerType,entryDate) 
            							VALUES ('$customers', '$salesId', '$paidAmount', '$loginID', '$paymentMethod', '$salesDate', 'Active', 'payment for Party Sales Code: $salesOrderNo', 'paymentReceived', 'PartySale', '$voucherReceiveNo', '$customerType','$toDay')";
            					$conn->query($sql);
            				}else{
            				    $paidAmount = 0;
            				}*/
					$conn->commit();
					unset($_SESSION["temporarySaleShopping_cart"]);
					$data = array(
						'msg' => 'Success',
						'salesId' => $salesId
					);
					echo json_encode($data);
				} else if ($checkOverQuantity > 0) {
					echo json_encode('Product Stock is not available');
					$conn->rollBack();
				} else {
					echo json_encode(print_r($salesProductArray));
					$conn->rollBack();
				}
			} else {
				$error++;
				echo json_encode("Product quantity must be lower then available quantity" . $sql);
				$conn->rollBack();
			}
		} else {
			echo json_encode("Product quantity must be lower then available quantity" . print_r($wirehouseProduct));
		}
	} else if ($_POST["action"] == 'check_out_cart') {
		$error = 0;
		$loginID = $_SESSION['user'];
		$salesDate = $_POST['salesDate'];
		$customers = $_POST['customers'];
		$salesMan = $_POST['salesMan'];
		$totalAmount = $_POST['totalAmount'];
		$totalProductDiscount = $_POST['totalProductDiscount'];
		$salesDiscount = $_POST['salesDiscount'];
		$totalDiscount = $_POST['totalDiscount'];
		$grandTotal = $_POST['grandTotal'];
		$paidAmount = $_POST['paidAmount'];
		$paymentMethod = $_POST['paymentMethod'];
		$vat = $_POST['vat'];
		$ait = $_POST['ait'];
		$carringCost = $_POST['carringCost'];
		// added by hamid
		if ($vat == '') {
			$vat = 0;
		}
		if ($ait == '') {
			$ait = 0;
		}
		if ($carringCost == '') {
			$carringCost = 0;
		}
		// End
		$requisitionNo = $_POST['requisitionNo'];
		$wareHouse = $_POST['wareHouse'];
		$productId = $_POST['productId'];
		$productQuantity = $_POST['productQuantity'];
		$productPrice = $_POST['productPrice'];
		$productDiscount = $_POST['productDiscount'];
		$productTotal = $_POST['productTotal'];
		$warehouse_ids = $_POST['warehouse_id'];
		$referenceInfo = $_POST['referenceInfo'];
		$invremarks = $_POST['remarks'];
		$type = $_POST['type'];
		$productIdArray = explode("@!@,", $productId);
		$productQuantityArray = explode("@!@,", $productQuantity);
		$productPriceArray = explode("@!@,", $productPrice);
		$productDiscountArray = explode("@!@,", $productDiscount);
		$productTotalArray = explode("@!@,", $productTotal);
		$warehouse_idArray = explode("@!@,", $warehouse_ids);
		$salesOrderNo = '';
		$total_price = 0;
		$total_item = 0;
		$warehouseWiseProductArray = array(array());
		$voucherEntryArray = array(array());
		$salesEntry = array();
		$transport = 0;
		try {
			$checkOverQuantity = 0;
			$grandTotalAfterOffer = 0;
			if (isset($_SESSION["temporarySaleShopping_cart"])) {
				$data = '';
				if (count($_SESSION["temporarySaleShopping_cart"]) == count($productIdArray)) {
					$i = 0;
					$checkErrorManualMsg = '';
					foreach ($_SESSION["temporarySaleShopping_cart"] as $keys => $values) {
						$warehouseWiseProductArray[$i]['product_id'] = $values['product_id'];
						$warehouseWiseProductArray[$i]['product_quantity'] = $values['product_quantity'];
						$warehouseWiseProductArray[$i]['warehouse_id'] = $values['warehouse_id'];
						if (isset($newArray[$values['product_id']])) {
							$newArray[$values['product_id']] = $newArray[$values['product_id']] + $values['product_quantity'];
						} else {
							$newArray[$values['product_id']] = $values['product_quantity'];
						}
						$i++;
					}
					//$checkOverQuantity++;
					//$checkErrorManualMsg = $newArray[0];

				} else {
					$checkOverQuantity++;
					$checkErrorManualMsg = "Session and Cart data not matched... Please try again or contact with administrator.";
				}
				$checkErrorManual = 1;
			}
			$i = 0;
			$salesProductArray = array(array());
			$newProductQtyArray = array(array());
			$row = 0;
			foreach ($_SESSION["temporarySaleShopping_cart"] as $keys => $values) {
				$i++;
				if ($values['status'] == 0) {
					$productIdEntry = $values['product_id'];
					$productQuantityEntry = $newArray[$productIdEntry];
					$productDiscountEntry = $values['product_discount'];
					$productPriceEntry = $values['product_price'];
					$product_name = str_replace(",", ".", $values['product_name']);;
					//$product_name = $values['product_name'];
					$sql_discountOffer = "SELECT id,offer_applicable,offer_for,unit_for,discount,discount_unit,discount_2,discount_unit_2, offer_name
                                    FROM tbl_discount_offer 
									WHERE '$toDate' BETWEEN date_from AND date_to AND deleted = 'No' AND status='Active' AND tbl_products_id = '" . $productIdEntry . "' AND offer_applicable='TS' AND priority > 0
                                    ORDER BY offer_for DESC, priority DESC";
					//echo $sql_discountOffer;
					$result_discountOffer = $conn->query($sql_discountOffer);
					$discount_pc = 0;
					$discount_amount = 0;
					$rest_pc = 0;
					$rest_amount = 0;
					$test = 0;
					$discountOfferValue = 0;
					$stock_check = 'On';
					$total_quantity = $productQuantityEntry;
					if ($result_discountOffer->num_rows > 0) {
						while ($row_discountOffer = $result_discountOffer->fetch_assoc()) {
							$discountOfferid = $row_discountOffer['id'];
							if ($row_discountOffer['unit_for'] == 'PC') {
								if ($row_discountOffer['discount_unit'] == 'PC') {
									$discount_pc = floor($total_quantity / $row_discountOffer['offer_for']);
									$discount_amount = '100%';
									$rest_pc = $discount_pc * $row_discountOffer['offer_for'];
									if ($rest_pc > 0) {
										if ($row_discountOffer['discount_unit_2'] == 'TK') {
											if ($row_discountOffer['discount_2'] != 0) {
												$discountOfferValue = ($row_discountOffer['discount_2'] * $discount_pc);
											} else {
												$discountOfferValue = $productDiscountEntry;
											}
										} else {
											$discountOfferValue = $productDiscountEntry;
										}
										$productTotal = $rest_pc * $productPriceEntry;
										if ($discountOfferValue != "") {
											$lastValue = substr($discountOfferValue, -1);
											if ($lastValue == "%") {
												$productDiscount = $productTotal * (substr($discountOfferValue, 0, -1) / 100);
											} else {
												$productDiscount = $discountOfferValue;
											}
											$productTotal = $productTotal - $productDiscount;
										}
										if ($discount_pc >= 1) {
											$discount_pc = $discount_pc * $row_discountOffer['discount'];
										}
										$amount = $productTotal / $rest_pc;
										$calling_qty = $rest_pc;
										/*$sql = "INSERT INTO tbl_tsalesproducts (tbl_tSalesId, tbl_productsId, quantity, units, createdBy, saleAmount, amount, totalAmount, discount, grandTotal, remarks, tbl_wareHouseId,createdDate) 
                            						        VALUES ('$salesId','$productIdEntry','$rest_pc','','$loginID','$productPriceEntry','$amount','$productTotal','$discountOfferValue','$productTotal','$productDiscount','$warehouse_idEntry','$toDay')";*/
										for ($i = 0; $i < count($warehouseWiseProductArray); $i++) {
											if ($warehouseWiseProductArray[$i]['product_id'] == $productIdEntry && $calling_qty > 0 && $warehouseWiseProductArray[$i]['product_quantity'] > 0) {
												if ($warehouseWiseProductArray[$i]['product_quantity'] >= $calling_qty) {
													$salesProductArray[$row][0] = $productIdEntry;
													$salesProductArray[$row][1] = $calling_qty;
													$salesProductArray[$row][2] = '';
													$salesProductArray[$row][3] = $productPriceEntry;
													$salesProductArray[$row][4] = ($productTotal / $rest_pc) * $calling_qty;
													$salesProductArray[$row][5] = ($discountOfferValue / $rest_pc) * $calling_qty;
													$salesProductArray[$row][6] = ($productTotal / $rest_pc) * $calling_qty;
													$salesProductArray[$row][7] = ($productDiscount / $rest_pc) * $calling_qty;
													$salesProductArray[$row][8] = 0;
													$salesProductArray[$row][9] = $warehouseWiseProductArray[$i]['warehouse_id'];
													$salesProductArray[$row][10] = $row;
													$salesProductArray[$row][11] = $product_name;
													$salesProductArray[$row][12] = '';
													$salesProductArray[$row][13] = $amount;
													$row++;
													$warehouseWiseProductArray[$i]['product_quantity'] -= $calling_qty;
													$calling_qty = 0;
												} else {
													$salesProductArray[$row][0] = $productIdEntry;
													$salesProductArray[$row][1] = $warehouseWiseProductArray[$i]['product_quantity'];
													$salesProductArray[$row][2] = '';
													$salesProductArray[$row][3] = $productPriceEntry;
													$salesProductArray[$row][4] = ($productTotal / $rest_pc) * $warehouseWiseProductArray[$i]['product_quantity'];
													$salesProductArray[$row][5] = ($discountOfferValue / $rest_pc) * $warehouseWiseProductArray[$i]['product_quantity'];;
													$salesProductArray[$row][6] = ($productTotal / $rest_pc) * $warehouseWiseProductArray[$i]['product_quantity'];
													$salesProductArray[$row][7] = ($productDiscount / $rest_pc) * $warehouseWiseProductArray[$i]['product_quantity'];
													$salesProductArray[$row][8] = 0;
													$salesProductArray[$row][9] = $warehouseWiseProductArray[$i]['warehouse_id'];
													$salesProductArray[$row][10] = $row;
													$salesProductArray[$row][11] = $product_name;
													$salesProductArray[$row][12] = '';
													$salesProductArray[$row][13] = $amount;
													$row++;
													$calling_qty -= $warehouseWiseProductArray[$i]['product_quantity'];
													$warehouseWiseProductArray[$i]['product_quantity'] = 0;
												}
											} else if ($calling_qty == 0) {
												break;
											}
										}
										$newProductQtyArray[$productIdEntry][0] = $productIdEntry;
										$newProductQtyArray[$productIdEntry][1] += $rest_pc;
										$grandTotalAfterOffer += $productTotal;
										$total_price = $total_price + $productTotal;
										$total_item = $total_item + 1;
										$totalProductDiscount = $totalProductDiscount + $productDiscount;
									}

									if ($discount_pc > 0) {
										$productTotal = $discount_pc * $productPriceEntry;
										$productDiscount = $productTotal;
										$productTotal = $productTotal - $productDiscount;
										$amount = $productTotal / $discount_pc;
										/*$sql = "INSERT INTO tbl_tsalesproducts (tbl_tSalesId, tbl_productsId, quantity, units, createdBy, saleAmount, amount, totalAmount, discount, grandTotal, remarks, tbl_wareHouseId,createdDate,tbl_discount_offer_id) 
                            						        VALUES ('$salesId','$productIdEntry','$discount_pc','','$loginID','$productPriceEntry','$amount','$productTotal','100%','$productTotal','$productDiscount','$warehouse_idEntry','$toDay','$discountOfferid')";*/
										$sql_availableWarehouse = "SELECT tbl_warehouse.id, tbl_warehouse.wareHouseName
                                                            FROM `tbl_currentStock`
                                                            INNER JOIN tbl_warehouse ON tbl_currentStock.tbl_wareHouseId = tbl_warehouse.id
                                                            WHERE tbl_currentStock.tbl_productsId = '$productIdEntry' AND tbl_currentStock.deleted = 'No' AND tbl_warehouse.deleted = 'No' AND tbl_currentStock.currentStock > '$discount_pc'";
										$result_availableWarehouse = $conn->query($sql_availableWarehouse);
										$availableWarehouse = "<option value=''>Select Warehouse</option>";
										while ($row_availableWarehouse = $result_availableWarehouse->fetch_assoc()) {
											$availableWarehouse .= "<option value='" . $row_availableWarehouse['id'] . "'>" . $row_availableWarehouse['wareHouseName'] . "</option>";
										}
										$salesProductArray[$row][0] = $productIdEntry;
										$salesProductArray[$row][1] = $discount_pc;
										$salesProductArray[$row][2] = '';
										$salesProductArray[$row][3] = $productPriceEntry;
										$salesProductArray[$row][4] = $productTotal;
										$salesProductArray[$row][5] = '100%';
										$salesProductArray[$row][6] = $productTotal;
										$salesProductArray[$row][7] = $productDiscount;
										$salesProductArray[$row][8] = $discountOfferid;
										$salesProductArray[$row][9] = 0;
										$salesProductArray[$row][10] = $row;
										$salesProductArray[$row][11] = $product_name;
										$salesProductArray[$row][12] = $availableWarehouse;
										$salesProductArray[$row][13] = $amount;
										$row++;
										$newProductQtyArray[$productIdEntry][0] = $productIdEntry;
										$newProductQtyArray[$productIdEntry][1] += $discount_pc;
										$grandTotalAfterOffer += $productTotal;
										$total_price = $total_price + $productTotal;
										$total_item = $total_item + 1;
										$totalProductDiscount = $totalProductDiscount + $productDiscount;
									}

									$total_quantity = $total_quantity - ($rest_pc);
								}

								if ($stock_check == 'On') {

									if ($row_discountOffer['discount_unit'] == '%') {
										if ($total_quantity >= $row_discountOffer['offer_for']) {
											$discount_quantity = $row_discountOffer['offer_for'] * floor($total_quantity / $row_discountOffer['offer_for']);
											$productTotal = $discount_quantity * $productPriceEntry;
											$productDiscount = $productTotal * ($row_discountOffer['discount'] / 100);


											$productTotal = $productTotal - $productDiscount;
											$amount = $productTotal / $discount_quantity;
											/*$sql = "INSERT INTO tbl_tsalesproducts (tbl_tSalesId, tbl_productsId, quantity, units, createdBy, saleAmount, amount, totalAmount         , discount, grandTotal, remarks, tbl_wareHouseId,createdDate, tbl_discount_offer_id) 
                            						        VALUES ('$salesId','$productIdEntry','$discount_quantity','','$loginID','$productPriceEntry','$amount','$productTotal','".$row_discountOffer['discount']."%','$productTotal','$productDiscount','$warehouse_idEntry','$toDay','$discountOfferid')";*/
											$calling_qty = $discount_quantity;
											for ($i = 0; $i < count($warehouseWiseProductArray); $i++) {
												if ($warehouseWiseProductArray[$i]['product_id'] == $productIdEntry && $calling_qty > 0 && $warehouseWiseProductArray[$i]['product_quantity'] > 0) {
													if ($warehouseWiseProductArray[$i]['product_quantity'] >= $calling_qty) {
														$salesProductArray[$row][0] = $productIdEntry;
														$salesProductArray[$row][1] = $calling_qty;
														$salesProductArray[$row][2] = '';
														$salesProductArray[$row][3] = $productPriceEntry;
														$salesProductArray[$row][4] = $productTotal;
														$salesProductArray[$row][5] = $row_discountOffer['discount'] . '%';
														$salesProductArray[$row][6] = $productTotal;
														$salesProductArray[$row][7] = $productDiscount;
														$salesProductArray[$row][8] = $discountOfferid;
														$salesProductArray[$row][9] = $warehouseWiseProductArray[$i]['warehouse_id'];
														$salesProductArray[$row][10] = $row;
														$salesProductArray[$row][11] = $product_name;
														$salesProductArray[$row][12] = '';
														$salesProductArray[$row][13] = $amount;
														$row++;
														$warehouseWiseProductArray[$i]['product_quantity'] -= $calling_qty;
														$calling_qty = 0;
													} else {
														$salesProductArray[$row][0] = $productIdEntry;
														$salesProductArray[$row][1] = $warehouseWiseProductArray[$i]['product_quantity'];
														$salesProductArray[$row][2] = '';
														$salesProductArray[$row][3] = $productPriceEntry;
														$salesProductArray[$row][4] = $productTotal;
														$salesProductArray[$row][5] = $row_discountOffer['discount'] . '%';
														$salesProductArray[$row][6] = $productTotal;
														$salesProductArray[$row][7] = $productDiscount;
														$salesProductArray[$row][8] = $discountOfferid;
														$salesProductArray[$row][9] = $warehouseWiseProductArray[$i]['warehouse_id'];
														$salesProductArray[$row][10] = $row;
														$salesProductArray[$row][11] = $product_name;
														$salesProductArray[$row][12] = '';
														$salesProductArray[$row][13] = $amount;
														$row++;
														$calling_qty -= $warehouseWiseProductArray[$i]['product_quantity'];
														$warehouseWiseProductArray[$i]['product_quantity'] = 0;
													}
												} else if ($calling_qty == 0) {
													break;
												}
											}
											$newProductQtyArray[$productIdEntry][0] = $productIdEntry;
											$newProductQtyArray[$productIdEntry][1] += $discount_quantity;
											$grandTotalAfterOffer += $productTotal;
											$total_price = $total_price + $productTotal;
											$total_item = $total_item + 1;
											$totalProductDiscount = $totalProductDiscount + $productDiscount;
											$total_quantity = $total_quantity - $discount_quantity;
										}
									}
									if ($row_discountOffer['discount_unit'] == 'TK') {
										if ($total_quantity >= $row_discountOffer['offer_for']) {
											$discount_quantity = $row_discountOffer['offer_for'] * floor($total_quantity / $row_discountOffer['offer_for']);
											$productDiscount = $row_discountOffer['discount'] * floor($total_quantity / $row_discountOffer['offer_for']);
											$productTotal = ($discount_quantity * $productPriceEntry) - $productDiscount;
											$amount = $productTotal / $discount_quantity;
											/*$sql = "INSERT INTO tbl_tsalesproducts (tbl_tSalesId, tbl_productsId, quantity, units, createdBy,saleAmount, amount, totalAmount, discount, grandTotal, remarks, tbl_wareHouseId,createdDate, tbl_discount_offer_id) 
                            						        VALUES ('$salesId','$productIdEntry','$discount_quantity','','$loginID','$productPriceEntry','$amount','$productTotal','$productDiscount','$productTotal','$productDiscount','$warehouse_idEntry','$toDay','$discountOfferid')";*/
											$calling_qty = $discount_quantity;
											for ($i = 0; $i < count($warehouseWiseProductArray); $i++) {
												if ($warehouseWiseProductArray[$i]['product_id'] == $productIdEntry && $calling_qty > 0 && $warehouseWiseProductArray[$i]['product_quantity'] > 0) {
													if ($warehouseWiseProductArray[$i]['product_quantity'] >= $calling_qty) {
														$salesProductArray[$row][0] = $productIdEntry;
														$salesProductArray[$row][1] = $calling_qty;
														$salesProductArray[$row][2] = '';
														$salesProductArray[$row][3] = $productPriceEntry;
														$salesProductArray[$row][4] = $productTotal;
														$salesProductArray[$row][5] = $productDiscount;
														$salesProductArray[$row][6] = $productTotal;
														$salesProductArray[$row][7] = $productDiscount;
														$salesProductArray[$row][8] = $discountOfferid;
														$salesProductArray[$row][9] = $warehouseWiseProductArray[$i]['warehouse_id'];
														$salesProductArray[$row][10] = $row;
														$salesProductArray[$row][11] = $product_name;
														$salesProductArray[$row][12] = '';
														$salesProductArray[$row][13] = $amount;
														$row++;
														$warehouseWiseProductArray[$i]['product_quantity'] -= $calling_qty;
														$calling_qty = 0;
													} else {
														$salesProductArray[$row][0] = $productIdEntry;
														$salesProductArray[$row][1] = $warehouseWiseProductArray[$i]['product_quantity'];
														$salesProductArray[$row][2] = '';
														$salesProductArray[$row][3] = $productPriceEntry;
														$salesProductArray[$row][4] = $productTotal;
														$salesProductArray[$row][5] = $productDiscount;
														$salesProductArray[$row][6] = $productTotal;
														$salesProductArray[$row][7] = $productDiscount;
														$salesProductArray[$row][8] = $discountOfferid;
														$salesProductArray[$row][9] = $warehouseWiseProductArray[$i]['warehouse_id'];
														$salesProductArray[$row][10] = $row;
														$salesProductArray[$row][11] = $product_name;
														$salesProductArray[$row][12] = '';
														$salesProductArray[$row][13] = $amount;
														$row++;
														$calling_qty -= $warehouseWiseProductArray[$i]['product_quantity'];
														$warehouseWiseProductArray[$i]['product_quantity'] = 0;
													}
												} else if ($calling_qty == 0) {
													break;
												}
											}
											$newProductQtyArray[$productIdEntry][0] = $productIdEntry;
											$newProductQtyArray[$productIdEntry][1] += $discount_quantity;
											$grandTotalAfterOffer += $productTotal;
											$total_price = $total_price + $productTotal;
											$total_item = $total_item + 1;
											$totalProductDiscount = $totalProductDiscount + ($productDiscount * $total_quantity);
											$total_quantity = $total_quantity - $discount_quantity;
										}
									}
								}
							}
						}
					} else {
						$rest_pc = $total_quantity;
						$discount_pc = 0;
						$discount_amount = 0;
					}
					$productDiscount = 0;
					if ($total_quantity > 0) {

						$productTotal = $total_quantity * $productPriceEntry;
						if ($productDiscountEntry != "") {
							$lastValue = substr($productDiscountEntry, -1);
							if ($lastValue == "%") {
								$productDiscount = $productTotal * (substr($productDiscountEntry, 0, -1) / 100);
							} else {
								$productDiscount = $productDiscountEntry;
							}
							$productTotal = $productTotal - $productDiscount;
						}
						$amount = $productTotal / $total_quantity;
						/*$sql = "INSERT INTO tbl_tsalesproducts (tbl_tSalesId, tbl_productsId, quantity, units, createdBy, saleAmount, amount, totalAmount, discount, grandTotal, remarks, tbl_wareHouseId,createdDate) 
        						        VALUES ('$salesId','$productIdEntry','$total_quantity','','$loginID', '$productPriceEntry', '$amount','$productTotal','$productDiscountEntry','$productTotal','$productDiscount','$warehouse_idEntry','$toDay')";*/
						$calling_qty = $total_quantity;
						for ($i = 0; $i < count($warehouseWiseProductArray); $i++) {
							if ($warehouseWiseProductArray[$i]['product_id'] == $productIdEntry && $calling_qty > 0 && $warehouseWiseProductArray[$i]['product_quantity'] > 0) {
								if ($warehouseWiseProductArray[$i]['product_quantity'] >= $calling_qty) {
									$salesProductArray[$row][0] = $productIdEntry;
									$salesProductArray[$row][1] = $calling_qty;
									$salesProductArray[$row][2] = '';
									$salesProductArray[$row][3] = $productPriceEntry;
									$salesProductArray[$row][4] = ($productTotal / $total_quantity) * $calling_qty;
									$salesProductArray[$row][5] = ($productDiscount / $total_quantity) * $calling_qty;
									$salesProductArray[$row][6] = ($productTotal / $total_quantity) * $calling_qty;
									$salesProductArray[$row][7] = ($productDiscount / $total_quantity) * $calling_qty;
									$salesProductArray[$row][8] = 0;
									$salesProductArray[$row][9] = $warehouseWiseProductArray[$i]['warehouse_id'];
									$salesProductArray[$row][10] = $row;
									$salesProductArray[$row][11] = $product_name;
									$salesProductArray[$row][12] = '';
									/*$salesProductArray[$row][0] = $productIdEntry;
                    					    $salesProductArray[$row][1] = $calling_qty;
                    					    $salesProductArray[$row][2] = '';
                    					    $salesProductArray[$row][3] = $productPriceEntry;
                    					    $salesProductArray[$row][4] = $productTotal;
                    					    $salesProductArray[$row][5] = $productDiscountEntry;
                    					    $salesProductArray[$row][6] = $productTotal;
                    					    $salesProductArray[$row][7] = $productDiscount;
                    					    $salesProductArray[$row][8] = 0;
                    					    $salesProductArray[$row][9] = $warehouseWiseProductArray[$i]['warehouse_id'];
                    					    $salesProductArray[$row][10] = $row;
                    					    $salesProductArray[$row][11] = $product_name;
                    					    $salesProductArray[$row][12] = '';*/
									$salesProductArray[$row][13] = $amount;
									$row++;
									$warehouseWiseProductArray[$i]['product_quantity'] -= $calling_qty;
									$calling_qty = 0;
								} else {
									$salesProductArray[$row][0] = $productIdEntry;
									$salesProductArray[$row][1] = $warehouseWiseProductArray[$i]['product_quantity'];
									$salesProductArray[$row][2] = '';
									$salesProductArray[$row][3] = $productPriceEntry;
									$salesProductArray[$row][4] = ($productTotal / $total_quantity) * $warehouseWiseProductArray[$i]['product_quantity'];
									$salesProductArray[$row][5] = ($productDiscount / $total_quantity) * $warehouseWiseProductArray[$i]['product_quantity'];
									$salesProductArray[$row][6] = ($productTotal / $total_quantity) * $warehouseWiseProductArray[$i]['product_quantity'];
									$salesProductArray[$row][7] = ($productDiscount / $total_quantity) * $warehouseWiseProductArray[$i]['product_quantity'];
									$salesProductArray[$row][8] = 0;
									$salesProductArray[$row][9] = $warehouseWiseProductArray[$i]['warehouse_id'];
									$salesProductArray[$row][10] = $row;
									$salesProductArray[$row][11] = $product_name;
									$salesProductArray[$row][12] = '';
									/*$salesProductArray[$row][0] = $productIdEntry;
                    					    $salesProductArray[$row][1] = $warehouseWiseProductArray[$i]['product_quantity'];
                    					    $salesProductArray[$row][2] = '';
                    					    $salesProductArray[$row][3] = $productPriceEntry;
                    					    $salesProductArray[$row][4] = $productTotal;
                    					    $salesProductArray[$row][5] = $discountOfferValue;
                    					    $salesProductArray[$row][6] = $productTotal;
                    					    $salesProductArray[$row][7] = $productDiscount;
                    					    $salesProductArray[$row][8] = 0;
                    					    $salesProductArray[$row][9] = $warehouseWiseProductArray[$i]['warehouse_id'];
                    					    $salesProductArray[$row][10] = $row;
                    					    $salesProductArray[$row][11] = $product_name;
                    					    $salesProductArray[$row][12] = '';*/
									$salesProductArray[$row][13] = $amount;
									$row++;
									$calling_qty -= $warehouseWiseProductArray[$i]['product_quantity'];
									$warehouseWiseProductArray[$i]['product_quantity'] = 0;
								}
							} else if ($calling_qty == 0) {
								break;
							}
						}
						$newProductQtyArray[$productIdEntry][0] = $productIdEntry;
						//$newProductQtyArray[$productIdEntry][1]+=$total_quantity;
						$newProductQtyArray[$productIdEntry][0] += $total_quantity;
						$grandTotalAfterOffer += $productTotal;
						$total_price = $total_price + $productTotal;
						$total_item = $total_item + 1;
						$totalProductDiscount = $totalProductDiscount + $productDiscount;
					}
				}
			}
			if ($checkOverQuantity == 0) {
				$conn->begin_transaction();
				$salesEntry[0] = $salesDate;
				$salesEntry[1] = $customers;
				$salesEntry[2] = $salesMan;
				$salesEntry[3] = $totalAmount;
				$salesEntry[4] = $totalProductDiscount;
				$salesEntry[5] = $salesDiscount;
				$salesEntry[6] = $totalDiscount;
				$salesEntry[7] = $grandTotal;
				$salesEntry[8] = $vat;
				$salesEntry[9] = $ait;
				$salesEntry[10] = $type;
				$salesEntry[11] = $paymentMethod;
				$salesEntry[12] = $invremarks;
				$salesEntry[13] = $carringCost;
				$salesEntry[14] = $requisitionNo;
				$salesEntry[15] = $transport;
				$salesEntry[16] = $referenceInfo;
				$salesEntry[17] = $paidAmount;
				//$salesEntry[19]=$referenceInfo;

				/*$sql = "INSERT INTO tbl_temporary_sale (tsNo, tSalesDate, tbl_customerId, paymentType, inv_remarks, remarks, createdBy, tbl_userId, requisitionNo, tbl_wareHouseId, referenceInfo, createdDate)
            	        VALUES ('$salesOrderNo','$salesDate','$customers','$paymentMethod','$invremarks','Temporary Sales with salesCode: .$salesOrderNo','$loginID','$salesMan','$requisitionNo','$wareHouse','$referenceInfo','$toDay')";
            	if($conn->query($sql)){
            	    $salesId = $conn->insert_id;*/
				if ($error == 0) {


					$grandTotalAfterOffer = round($grandTotalAfterOffer - $salesDiscount + $vat + $ait + $carringCost);
					$salesEntry[18] = $grandTotalAfterOffer;
					/*$sql = "INSERT INTO tbl_paymentVoucher (tbl_partyId, tbl_sales_id, amount, entryBy, paymentMethod, paymentDate, status, remarks, type, voucherType, voucherNo, customerType,entryDate) 
        							VALUES ('$customers', '$salesId', '$grandTotalAfterOffer', '$loginID', '$paymentMethod', '$salesDate', 'Active', 'Payable for Party Sales Code: $salesOrderNo', 'partyPayable', 'PartySale', '$voucherNo', '$customerType','$toDay')";
        					$conn->query($sql);
        					$voucherEntryArray[0][0] = $customers;
        					$voucherEntryArray[0][1] = $grandTotalAfterOffer;
        					$voucherEntryArray[0][2] = $paymentMethod;
        					$voucherEntryArray[0][3] = 'partyPayable';
        					$voucherEntryArray[0][4] = 'PartySale';*/
					if ($paidAmount > 0) {
						/*$sql = "INSERT INTO tbl_paymentVoucher (tbl_partyId, tbl_sales_id, amount, entryBy, paymentMethod, paymentDate, status, remarks, type, voucherType, voucherNo, customerType,entryDate) 
        							VALUES ('$customers', '$salesId', '$paidAmount', '$loginID', '$paymentMethod', '$salesDate', 'Active', 'payment for Party Sales Code: $salesOrderNo', 'paymentReceived', 'PartySale', '$voucherReceiveNo', '$customerType','$toDay')";
        					$conn->query($sql);
        					$voucherEntryArray[1][0] = $customers;
        					$voucherEntryArray[1][1] = $paidAmount;
        					$voucherEntryArray[1][2] = $paymentMethod;
        					$voucherEntryArray[1][3] = 'paymentReceived';
        					$voucherEntryArray[1][4] = 'PartySale';*/
					} else {
						$paidAmount = 0;
					}


					$conn->commit();
					//unset($_SESSION["wholeSaleShopping_cart"]);
					$data = array(
						'msg' => 'Success',
						'sales' => $salesEntry,
						'salesProduct' => $salesProductArray
					);
					echo json_encode($data);
				} else if ($checkOverQuantity > 0) {
					echo json_encode('Product Stock is not available');
					$conn->rollBack();
				} else {
					echo json_encode(print_r($salesProductArray));
					$conn->rollBack();
				}
			} else if ($checkErrorManual == 1) {
				$error++;
				echo json_encode($checkErrorManualMsg);
				$conn->rollBack();
			} else {
				$error++;
				echo json_encode("Product quantity must be lower then available quantity");
				$conn->rollBack();
			}



























			/*if ($checkOverQuantity == 0){
        		$conn->begin_transaction();
        		$sql = "SELECT LPAD(max(tsNo)+1, 6, 0) as salesCode from tbl_temporary_sale";
        		$query = $conn->query($sql);
        		while ($prow = $query->fetch_assoc()) {
        			$salesOrderNo = $prow['salesCode'];
        		}
        		if($salesOrderNo == ""){
        		    $salesOrderNo = "000001";
        		}
        		$grandTotalAfterOffer=0;
    	        $sql = "INSERT INTO tbl_temporary_sale (tsNo, tSalesDate, tbl_customerId, paymentType, inv_remarks, remarks, createdBy, tbl_userId, requisitionNo, tbl_wareHouseId, referenceInfo, createdDate)
            	        VALUES ('$salesOrderNo','$salesDate','$customers','$paymentMethod','$invremarks','Temporary Sales with salesCode: .$salesOrderNo','$loginID','$salesMan','$requisitionNo','$wareHouse','$referenceInfo','$toDay')";
            	if($conn->query($sql)){
            	    $salesId = $conn->insert_id;
            	    for($i = 0; $i < count($productIdArray); $i++) {
    					$productIdEntry = $productIdArray[$i];
    					$productQuantityEntry =$productQuantityArray[$i]; 
    					$productPriceEntry = $productPriceArray[$i];
    					$productDiscountEntry =$productDiscountArray[$i]; 
    					$productTotalEntry = $productTotalArray[$i];
						$warehouse_idEntry = $warehouse_idArray[$i];
    					if($i == count($productIdArray)-1){
    						$productIdEntry = substr($productIdEntry, 0, strlen($productIdEntry)-3);
    						$productQuantityEntry = substr($productQuantityEntry, 0,strlen($productQuantityEntry)-3);
    						$productPriceEntry = substr($productPriceEntry, 0, strlen($productPriceEntry)-3);
    						$productDiscountEntry = substr($productDiscountEntry, 0,strlen($productDiscountEntry)-3);
    						$productTotalEntry = substr($productTotalEntry, 0, strlen($productTotalEntry)-3);
							$warehouse_idEntry = substr($warehouse_idEntry, 0, strlen($warehouse_idEntry)-3);
    					}
    					if($productIdEntry != ''){
    					    $total = $productQuantityEntry*$productPriceEntry;
    					    if(substr($productDiscountEntry, -1) == '%'){
    					        $discountAmount = $total*(substr($productDiscountEntry,0,-1)/100);
    					    }else{
    					        $discountAmount = $productDiscountEntry;
    					    }
    					    
    					    
    					    
    					    $sql_discountOffer = "SELECT id,offer_applicable,offer_for,unit_for,discount,discount_unit,discount_2,discount_unit_2, offer_name
                                    FROM tbl_discount_offer 
									WHERE '$toDate' BETWEEN date_from AND date_to AND deleted = 'No' AND tbl_products_id = '".$productIdEntry."' AND offer_applicable='TS' AND priority > 0
                                    ORDER BY priority DESC, offer_for DESC";
                            //echo $sql_discountOffer;
                            $result_discountOffer = $conn->query($sql_discountOffer);
                            $discount_pc = 0;
                            $discount_amount = 0;
                            $rest_pc = 0;
                            $rest_amount = 0;
                            $test = 0;
                            $stock_check = 'On';
                            $total_quantity = $productQuantityEntry;
                            if($result_discountOffer->num_rows > 0 ){
                                while($row_discountOffer = $result_discountOffer->fetch_assoc()){
                                    $discountOfferid = $row_discountOffer['id'];
                                    if($row_discountOffer['unit_for'] == 'PC'){
                						if($row_discountOffer['discount_unit'] == 'PC'){
                							$discount_pc = floor($total_quantity / $row_discountOffer['offer_for']);
                							$discount_amount = '100%';
                							$rest_pc = $discount_pc * $row_discountOffer['offer_for'];
                								if($rest_pc > 0){
                									if($row_discountOffer['discount_unit_2'] == 'TK'){
                										if($row_discountOffer['discount_2'] != 0){
                										$discountOfferValue = ($row_discountOffer['discount_2']*$discount_pc);
                									    }else{
                									        $discountOfferValue = $productDiscountEntry;
                									    }
                									}else{
                										$discountOfferValue = $productDiscountEntry;
                									}
                									$productTotal = $rest_pc * $productPriceEntry;
                									if($discountOfferValue != ""){
                										$lastValue = substr($discountOfferValue, -1);
                										if($lastValue == "%"){
                											$productDiscount = $productTotal * (substr($discountOfferValue, 0, -1)/100);
                										}else{
                											$productDiscount = $discountOfferValue;
                										}
                										$productTotal = $productTotal - $productDiscount;
                									}
                									if($discount_pc >= 1){
                        							    $discount_pc = $discount_pc * $row_discountOffer['discount'];
                        							}
                        							
                        							    $amount =$productTotal / $rest_pc;
                        						
                									$sql = "INSERT INTO tbl_tsalesproducts (tbl_tSalesId, tbl_productsId, quantity, units, createdBy, saleAmount, amount, totalAmount, discount, grandTotal, remarks, tbl_wareHouseId,createdDate) 
                            						        VALUES ('$salesId','$productIdEntry','$rest_pc','','$loginID','$productPriceEntry','$amount','$productTotal','$discountOfferValue','$productTotal','$productDiscount','$warehouse_idEntry','$toDay')";
                            						if($conn->query($sql)){
                            						    $grandTotalAfterOffer += $productTotal;
                                						$sql = "UPDATE tbl_currentStock 
                                								    set salesStock=salesStock+$rest_pc, currentStock=currentStock-$rest_pc, lastUpdatedDate='$toDay', lastUpdatedBy='$loginID'
                                								    where tbl_productsId = '$productIdEntry' AND tbl_wareHouseId='$wareHouse'";
                                					    $query1 = $conn->query($sql);
                                						if($conn->affected_rows == 0){
                                							$sql = "insert into tbl_currentStock 
                                							            (salesStock, currentStock, tbl_productsId, tbl_wareHouseId, entryBy,entryDate) values 
                                							            ('$rest_pc', '-$rest_pc','$productIdEntry','$wareHouse','$loginID','$toDay')";
                                							$query2 = $conn->query($sql);
                                						}
                                						if($query1 || $query2){
                                						    $sql = "UPDATE tbl_products 
                                                                    SET saleTime=saleTime+1
                                                                    WHERE id='$productIdEntry' AND deleted='No' AND status='Active'";
                                                            $conn->query($sql);
                                						    
                                						}
                            						}else{
                            						    $error++;
                                                        echo json_encode($conn->error.$sql);	    
                                                        $conn->rollBack();		    
                            						}
                									$total_price = $total_price + $productTotal;
                									$total_item = $total_item + 1;
                									$totalProductDiscount = $totalProductDiscount + $productDiscount;
                								}
                										
                								if($discount_pc > 0){
                									$productTotal = $rest_pc * $productPriceEntry;
                									$productDiscount = $productTotal;
                									$productTotal = $productTotal - $productDiscount;
                									
                    							    $amount =$productTotal / $discount_pc;
                        							
                									$sql = "INSERT INTO tbl_tsalesproducts (tbl_tSalesId, tbl_productsId, quantity, units, createdBy, saleAmount, amount, totalAmount, discount, grandTotal, remarks, tbl_wareHouseId,createdDate,tbl_discount_offer_id) 
                            						        VALUES ('$salesId','$productIdEntry','$discount_pc','','$loginID','$productPriceEntry','$amount','$productTotal','100%','$productTotal','$productDiscount','$warehouse_idEntry','$toDay','$discountOfferid')";
                            						if($conn->query($sql)){
                            						    $grandTotalAfterOffer += $productTotal;
                                						$sql = "UPDATE tbl_currentStock 
                                								    set salesStock=salesStock+$discount_pc, currentStock=currentStock-$discount_pc, lastUpdatedDate='$toDay', lastUpdatedBy='$loginID'
                                								    where tbl_productsId = '$productIdEntry' AND tbl_wareHouseId='$warehouse_idEntry'";
                                					    $query1 = $conn->query($sql);
                                						if($conn->affected_rows == 0){
                                							$sql = "insert into tbl_currentStock 
                                							            (salesStock, currentStock, tbl_productsId, tbl_wareHouseId, entryBy,entryDate) values 
                                							            ('$discount_pc', '-$discount_pc','$productIdEntry','$warehouse_idEntry','$loginID','$toDay')";
                                							$query2 = $conn->query($sql);
                                						}
                                						if($query1 || $query2){
                                						    $sql = "UPDATE tbl_products 
                                                                    SET saleTime=saleTime+1
                                                                    WHERE id='$productIdEntry' AND deleted='No' AND status='Active'";
                                                            $conn->query($sql);
                                						}
                            						}else{
                            						    $error++;
                                                        echo json_encode($conn->error.$sql);	    
                                                        $conn->rollBack();		    
                            						}
                									$total_price = $total_price + $productTotal;
                									$total_item = $total_item + 1;
                									$totalProductDiscount = $totalProductDiscount + $productDiscount;
                								}
                							
                							$total_quantity = $total_quantity - ($rest_pc);
                						}
                						
                						if($stock_check == 'On'){
                						   
                							if($row_discountOffer['discount_unit'] == '%'){
                								if($total_quantity >= $row_discountOffer['offer_for']){
                									$discount_quantity = $row_discountOffer['offer_for'] * floor($total_quantity / $row_discountOffer['offer_for']);
                									$productTotal = $discount_quantity * $productPriceEntry;
                									$productDiscount = $productTotal * ($row_discountOffer['discount']/100);
                								
                									
                									
                									$productTotal = $productTotal - $productDiscount;
                									$amount =$productTotal / $discount_quantity;
            										$sql = "INSERT INTO tbl_tsalesproducts (tbl_tSalesId, tbl_productsId, quantity, units, createdBy, saleAmount, amount, totalAmount         , discount, grandTotal, remarks, tbl_wareHouseId,createdDate, tbl_discount_offer_id) 
                            						        VALUES ('$salesId','$productIdEntry','$discount_quantity','','$loginID','$productPriceEntry','$amount','$productTotal','".$row_discountOffer['discount']."%','$productTotal','$productDiscount','$warehouse_idEntry','$toDay','$discountOfferid')";
                            						if($conn->query($sql)){
                            						    $grandTotalAfterOffer += $productTotal;
                                						$sql = "UPDATE tbl_currentStock 
                                								    set salesStock=salesStock+$discount_quantity, currentStock=currentStock-$discount_quantity, lastUpdatedDate='$toDay', lastUpdatedBy='$loginID'
                                								    where tbl_productsId = '$productIdEntry' AND tbl_wareHouseId='$warehouse_idEntry'";
                                					    $query1 = $conn->query($sql);
                                						if($conn->affected_rows == 0){
                                							$sql = "insert into tbl_currentStock 
                                							            (salesStock, currentStock, tbl_productsId, tbl_wareHouseId, entryBy,entryDate) values 
                                							            ('$discount_quantity', '-$discount_quantity','$productIdEntry','$warehouse_idEntry','$loginID','$toDay')";
                                							$query2 = $conn->query($sql);
                                						}
                                						if($query1 || $query2){
                                						    $sql = "UPDATE tbl_products 
                                                                    SET saleTime=saleTime+1
                                                                    WHERE id='$productIdEntry' AND deleted='No' AND status='Active'";
                                                            $conn->query($sql);
                                						}
                            						}else{
                            						    $error++;
                                                        echo json_encode($conn->error.$sql);	    
                                                        $conn->rollBack();		    
                            						}
                										
                										
                									$total_price = $total_price + $productTotal;
                									$total_item = $total_item + 1;
                									$totalProductDiscount = $totalProductDiscount + $productDiscount;
                									$total_quantity = $total_quantity - $discount_quantity;
                								}
                							}
                							if($row_discountOffer['discount_unit'] == 'TK'){
                								if($total_quantity >= $row_discountOffer['offer_for']){
                									$discount_quantity = $row_discountOffer['offer_for'] * floor($total_quantity / $row_discountOffer['offer_for']);
                									$productDiscount = $row_discountOffer['discount']* floor($total_quantity / $row_discountOffer['offer_for']);
                									
                									$productTotal = ($discount_quantity * $productPriceEntry) - $productDiscount;
                								
                									$amount =$productTotal / $discount_quantity;
            										$sql = "INSERT INTO tbl_tsalesproducts (tbl_tSalesId, tbl_productsId, quantity, units, createdBy,saleAmount, amount, totalAmount, discount, grandTotal, remarks, tbl_wareHouseId,createdDate, tbl_discount_offer_id) 
                            						        VALUES ('$salesId','$productIdEntry','$discount_quantity','','$loginID','$productPriceEntry','$amount','$productTotal','$productDiscount','$productTotal','$productDiscount','$warehouse_idEntry','$toDay','$discountOfferid')";
                            						if($conn->query($sql)){
                            						    $grandTotalAfterOffer += $productTotal;
                                						$sql = "UPDATE tbl_currentStock 
                                								    set salesStock=salesStock+$discount_quantity, currentStock=currentStock-$discount_quantity, lastUpdatedDate='$toDay', lastUpdatedBy='$loginID'
                                								    where tbl_productsId = '$productIdEntry' AND tbl_wareHouseId='$warehouse_idEntry'";
                                					    $query1 = $conn->query($sql);
                                						if($conn->affected_rows == 0){
                                							$sql = "insert into tbl_currentStock 
                                							            (salesStock, currentStock, tbl_productsId, tbl_wareHouseId, entryBy,entryDate) values 
                                							            ('$discount_quantity', '-$discount_quantity','$productIdEntry','$warehouse_idEntry','$loginID','$toDay')";
                                							$query2 = $conn->query($sql);
                                						}
                                						if($query1 || $query2){
                                						    $sql = "UPDATE tbl_products 
                                                                    SET saleTime=saleTime+1
                                                                    WHERE id='$productIdEntry' AND deleted='No' AND status='Active'";
                                                            $conn->query($sql);
                                						    
                                						}
                            						}else{
                            						    $error++;
                                                        echo json_encode($conn->error.$sql);	    
                                                        $conn->rollBack();		    
                            						}
                									$total_price = $total_price + $productTotal;
                									$total_item = $total_item + 1;
                									$totalProductDiscount = $totalProductDiscount + ($productDiscount*$total_quantity);
                									$total_quantity = $total_quantity - $discount_quantity;
                									
                									
                								}
                							}
                						}
                								
                					}
                                }
                            }else{
                                $rest_pc = $total_quantity;
                                $discount_pc = 0;
                                $discount_amount = 0;
                            }
                    	    $productDiscount = 0;
                    	    if($total_quantity > 0){
                        		
                    	        $productTotal = $total_quantity * $productPriceEntry;
                        	    if($productDiscountEntry != ""){
                        		    $lastValue = substr($productDiscountEntry, -1);
                        		    if($lastValue == "%"){
                        		        $productDiscount = $productTotal * (substr($productDiscountEntry, 0, -1)/100);
                        		    }else{
                        		        $productDiscount = $productDiscountEntry;
                        		    }
                        		    $productTotal = $productTotal - $productDiscount;
                        	    }
                        		$amount = $productTotal / $total_quantity;
                        		$sql = "INSERT INTO tbl_tsalesproducts (tbl_tSalesId, tbl_productsId, quantity, units, createdBy, saleAmount, amount, totalAmount, discount, grandTotal, remarks, tbl_wareHouseId,createdDate) 
        						        VALUES ('$salesId','$productIdEntry','$total_quantity','','$loginID', '$productPriceEntry', '$amount','$productTotal','$productDiscountEntry','$productTotal','$productDiscount','$warehouse_idEntry','$toDay')";
        						if($conn->query($sql)){
        						    $grandTotalAfterOffer += $productTotal;
            						$sql = "UPDATE tbl_currentStock 
            								    set salesStock=salesStock+$total_quantity, currentStock=currentStock-$total_quantity, lastUpdatedDate='$toDay', lastUpdatedBy='$loginID'
            								    where tbl_productsId = '$productIdEntry' AND tbl_wareHouseId='$warehouse_idEntry'";
            					    $query1 = $conn->query($sql);
            						if($conn->affected_rows == 0){
            							$sql = "insert into tbl_currentStock 
            							            (salesStock, currentStock, tbl_productsId, tbl_wareHouseId, entryBy,entryDate) values 
            							            ('$total_quantity', '-$total_quantity','$productIdEntry','$warehouse_idEntry','$loginID','$toDay')";
            							$query2 = $conn->query($sql);
            						}
            						if($query1 || $query2){
            						    $sql = "UPDATE tbl_products 
                                                SET saleTime=saleTime+1
                                                WHERE id='$productIdEntry' AND deleted='No' AND status='Active'";
                                        $conn->query($sql);
            						    
            						}
        						}else{
        						    $error++;
                                    echo json_encode($conn->error.$sql);	    
                                    $conn->rollBack();		    
        						}
                        		$total_price = $total_price + $productTotal;
                        		$total_item = $total_item + 1;
                                $totalProductDiscount = $totalProductDiscount + $productDiscount;
                    	    }
    					    
    					    
    					    
    					    
    					    
    					    
    					    
    					    
    					    
    					    
    					    
    					    
    					    
    					    
    					    
    					    
    					    
    					
    						
    						
    						
    					}
    				}
    				
    				if($error == 0){
        				$conn->commit();
                    	unset($_SESSION["temporarySaleShopping_cart"]);
                    	$data = array( 
                                        'msg'=>'Success', 
                                        'salesId'=>$salesId); 
            			echo json_encode($data);
    				}else{
    				    $conn->rollBack();
    				    echo json_encode("Error Code");
    				}
            	}else{
            	    $error++;
    		        echo json_encode($conn->error.$sql);	    
    	            $conn->rollBack();		    
            	}
    	    }else{
    	        $error++;
		        echo json_encode("Product quantity must be lower then available quantity");	    
	            $conn->rollBack();
    	    }*/
		} catch (Exception $e) {
			$conn->rollBack();
			echo json_encode('RollBack');
		}
	} elseif ($_POST["action"] == 'deleteSales') {
		$id = $_POST['id'];
		$sql = "SELECT tbl_sales_products.id, tbl_sales_products.quantity, tbl_sales_products.tbl_wareHouseId, tbl_sales_products.tbl_productsId, 'sales' AS adjustType 
                FROM tbl_sales_products
                LEFT OUTER JOIN tbl_tsalesproducts ON tbl_sales_products.tbl_TSProductsId = tbl_tsalesproducts.id AND tbl_tsalesproducts.deleted = 'No'
                WHERE tbl_sales_products.deleted='No' AND tbl_tsalesproducts.tbl_tSalesId='$id'
                UNION
                SELECT tbl_sales_product_return.id, tbl_sales_product_return.quantity, tbl_sales_product_return.tbl_wareHouseId, tbl_sales_product_return.tbl_products_id, 'saleReturn' AS adjustType
                FROM tbl_sales_product_return 
                LEFT OUTER JOIN tbl_tsalesproducts ON tbl_sales_product_return.tbl_salesProductsId = tbl_tsalesproducts.id AND tbl_tsalesproducts.deleted = 'No'
                WHERE tbl_sales_product_return.deleted='No' AND tbl_tsalesproducts.tbl_tSalesId='$id'";
		$result = $conn->query($sql);
		if ($result->num_rows > 0) {
			echo json_encode("Not possible to delete because this have final sale or return");
		} else {
			try {
				$loginID = $_SESSION['user'];
				$sql = "SELECT tbl_wareHouseId, tbl_productsId, quantity 
                        FROM tbl_tsalesproducts 
                        WHERE tbl_tsalesproducts.deleted='No' AND tbl_tsalesproducts.tbl_tSalesId='$id'";
				$resultSalesProducts = $conn->query($sql);
				$conn->begin_transaction();
				while ($row = $resultSalesProducts->fetch_assoc()) {
					$quantity = $row['quantity'];
					$tbl_productsId = $row['tbl_productsId'];
					$tbl_wareHouseId = $row['tbl_wareHouseId'];
					$sql = "UPDATE tbl_currentStock 
                            SET currentStock = currentStock+$quantity, salesDelete = salesDelete+$quantity, lastUpdatedDate='$toDay', lastUpdatedBy='$loginID' 
                            WHERE tbl_productsId='$tbl_productsId' AND tbl_wareHouseId='$tbl_wareHouseId' AND deleted='No'";
					$conn->query($sql);
				}
				$sql = "UPDATE tbl_tsalesproducts
                        SET deleted='Yes', deletedBy='$loginID', deletedDate='$toDay'
                        WHERE tbl_tSalesId='$id' AND deleted='No'";
				if ($conn->query($sql)) {
					$sql = "UPDATE tbl_temporary_sale 
                            SET deleted='Yes', deletedBy='$loginID', deletedDate='$toDay'
                            WHERE id='$id'";
					if ($conn->query($sql)) {


						//====================== Start Serialize Product Delete ======================//  
						$sql = "SELECT tbl_serialize_products_id, sale_quantity 
								FROM sale_serialize_products 
								WHERE tbl_temporary_sale_id='$id' AND deleted='No'";
						$resultSaleSerializeProducts = $conn->query($sql);
						$k = 0;
						while ($row = $resultSaleSerializeProducts->fetch_assoc()) {
							$tbl_serialize_products_id = $row['tbl_serialize_products_id'];
							$sale_quantity = intval($row['sale_quantity']);
							// Update
							$serialize_update_sql = "UPDATE tbl_serialize_products 
													 SET used_quantity=used_quantity-$sale_quantity, is_sold='ON', updated_by='$loginID', updated_date='$toDay'
													 WHERE id='$tbl_serialize_products_id' AND deleted='No'";
							$conn->query($serialize_update_sql);
							// End Update
							$k++;
						}
						// Delete
						$serialize_sale_delete_sql = "UPDATE sale_serialize_products
										  SET deleted='Yes', deleted_by='$loginID', deleted_date='$toDay' 
										  WHERE tbl_temporary_sale_id='$id' AND deleted='No'";
						$conn->query($serialize_sale_delete_sql);
						// End Delete
						//====================== End Serialize Product Delete ======================// 


						$conn->commit();
						echo json_encode('Success');
					} else {
						echo json_encode('Error: ' . $conn->error());
					}
				} else {
					echo json_encode('Error: ' . $conn->error());
				}
			} catch (Exception $e) {
				$conn->rollBack();
				echo json_encode($e->getMessage());
			}
		}
	} else if ($_POST['action'] == 'showSerializTable') {

		$rows = '';
		$product_id =  $_POST['id'];
		$warehouse_id =  $_POST['warehouseId'];
		$totalQuantityForSale = 0;
		$productSerialId = $_POST['product_id'];
		$totalMatchQuantity = 0;
		$checkSesssinStatus = "insert";
		$sql_serializeProducts = "SELECT tbl_serialize_products.id,tbl_serialize_products.tbl_productsId,tbl_serialize_products.purchase_id,tbl_serialize_products.serial_no,tbl_serialize_products.quantity,tbl_serialize_products.used_quantity 
		                            FROM tbl_serialize_products
		                            WHERE tbl_serialize_products.tbl_productsId = '$product_id' AND tbl_serialize_products.warehouse_id='$warehouse_id' AND tbl_serialize_products.deleted='No' AND tbl_serialize_products.status='Active' AND tbl_serialize_products.is_sold='ON' AND quantity>used_quantity
		                            ORDER BY tbl_serialize_products.id";
		$result_serializeProducts = $conn->query($sql_serializeProducts);
		if ($result_serializeProducts->num_rows > 0) {
			$key = 0;
			while ($row_serializeProducts = $result_serializeProducts->fetch_array()) {
				$remainingQty = ($row_serializeProducts['quantity'] - $row_serializeProducts['used_quantity']);
				$tblSerializeProductsId = $row_serializeProducts['id'];
				$saleQuantity = '';
				if (isset($_SESSION["temporarySaleShopping_cart"])) {
					foreach ($_SESSION["temporarySaleShopping_cart"] as $keys => $values) {
						$checkSesssinStatus = "update";
						
						if ($_SESSION["temporarySaleShopping_cart"][$keys]['product_id'] == $product_id && $_SESSION["temporarySaleShopping_cart"][$keys]['warehouse_id'] == $warehouse_id) {
							$serializeIdArray = $_SESSION["temporarySaleShopping_cart"][$keys]['serializeIdArray'];
							$item = array_search($tblSerializeProductsId, $serializeIdArray);
							//$item = in_array($tblSerializeProductsId, $serializeIdArray);
							if (is_int($item)) {
								$saleQuantity = $_SESSION["temporarySaleShopping_cart"][$keys]['serializeSaleQtyArray'][$item];
							} else {
								$saleQuantity = 0;
							}
							
						}
					}
				}
				$totalQuantityForSale += intval($saleQuantity);
				$rows .= '<tr><td>' . ($key + 1) . '</td>' .
					'<td>' . $row_serializeProducts['serial_no'] . '</td><td id="serializeRemainingQty_' . $tblSerializeProductsId . '">' . $remainingQty . '</td><td><input class="form-control only-number input-sm stockQuantity' . $key .
					'" id="stockQuantity_' . $tblSerializeProductsId . '" type="text" name="stockQuantity" placeholder=" ... " required oninput="calculateTotalQuantity(this.value,' . $productSerialId . ',' . $warehouse_id . ',' . $tblSerializeProductsId . ')" value="' . $saleQuantity . '"></td></tr>';

				$key++;
			}
		} else {
			$rows .= '<tr class="bg-warning"><td colspan="4">Stock Not Avaialable For Sale...</td></tr>';
		}
		echo json_encode(array('displayTable' => $rows, "totalQuantityForSale" => $totalQuantityForSale, "checkSesssinStatus" => $checkSesssinStatus));

	}
} else if (isset($_POST['fetchCart'])) {
	$total_price = 0;
	$total_item = 0;
	$totalProductDiscount = 0;
	$discountDisable = '';
	$amountDisable = '';
	$output = '<div class="table-responsive" id="order_table">
    	<table class="table table-bordered table-striped">
    		<tr style="background-color: #e1e1e1;font-size: 12px;">  
                <th style="width:25%;text-align: center;">Product Name</th>
				<th style="width:20%;text-align: center;">Warehouse</th>
                <th style="width:10%;text-align: center;">Quantity</th>
                <th style="width:10%;text-align: center;">Available</th>
                <th style="width:18%;text-align: center;">Price</th>  
                <th style="width:15%;text-align: center;">Discount</th>  
                <th style="width:22%;text-align: center;">Total</th>  
                <th style="width:3%;text-align: center;">Action</th>  
            </tr>';
	if (!empty($_SESSION["temporarySaleShopping_cart"])) {
		foreach ($_SESSION["temporarySaleShopping_cart"] as $keys => $values) {
			$productDiscount = 0;
			if (strtolower($_SESSION['userType']) == 'super admin' || strtolower($_SESSION['userType']) == 'admin support' || strtolower($_SESSION['userType']) == 'admin support plus') {
				$discountDisable = '';
				$amountDisable = '';
			} else if (strtolower($_SESSION['userType']) == 'admin coordinator') {
				$discountDisable = 'Disabled';
				$amountDisable = '';
			} else {
				$discountDisable = 'Disabled';
				$amountDisable = '';
			}

			$product_type =  $values["product_type"];
			$qtyDisable = '';
			if ($product_type == "serialize") {
				$qtyDisable = 'Disabled';
			}

			$output .= '
    		<tr style="background-color: #e1e1e1;font-size: 12px;">
    			<td>' . $values["product_name"] . '<input type="hidden" id="productId' . $values["id"] . '" name="productId" value="' . $values["product_id"] . '"/><input type="hidden" id="id' . $values["product_id"] . '" name="id" value="' . $values["id"] . '"/></td>
				<td>' . $values["warehouse_name"] . '<input type="hidden" id="warehouseId' . $values["id"] . '" name="warehouse_id" value="' . $values["warehouse_id"] . '"/><input type="hidden" id="warehouseName' . $values["id"] . '" name="warehouse_name" value="' . $values["warehouse_name"] . '"/></td>
    			<td><input '.$qtyDisable.' class=" saleQuantity_' . $values["product_id"] . '" type="text" id="productQuantity' . $values["id"] . '" name="productQuantity" value="' . $values["product_quantity"] . '" onkeyup="calculateTotal(' . $values["id"] . ')" onblur="updateSession(' . $values["id"] . ')" style="width: 100%;text-align: center;"/>';
			if ($values["product_type"] == "serialize") {
				$output .= '<a href="#" onclick="showSerializTable(' . $values["product_id"] . ', ' . $values["warehouse_id"] . ', ' . $values["product_quantity"] . ', ' . $values["id"] . ')"> <i class="fa fa-edit"> </i> </a>';
				$output .= '<input type="hidden" name="checkSerialize" value="' . $values["id"] . ',' . $values["warehouse_id"] . '" />';
			} else {
				$output .= '';
			}
			$output .= '</td>
    			<td><input type="text" id="availableQuantity' . $values["id"] . '" name="availableQuantity" value="' . $values["product_limit"] . '" style="width: 100%;text-align: center;" Readonly/></td>
    			<td align="right">
    			    <input type="text" id="productPrice' . $values["id"] . '" name="productPrice" value="' . $values["product_price"] . '" onkeyup="calculateTotal(' . $values["id"] . ')"  onblur="updateSession(' . $values["id"] . ')" style="width: 100%;text-align: center;" ' . $amountDisable . '/>
    			    <input type="hidden" id="productMaxPrice' . $values["id"] . '" name="productMaxPrice" value="' . $values["max_price"] . '"/>
    			    <input type="hidden" id="productMinPrice' . $values["id"] . '" name="productMinPrice" value="' . $values["min_price"] . '"/>
			    </td>
    			<td align="right"><input type="text" id="productDiscount' . $values["id"] . '" name="productDiscount" value="' . $values["product_discount"] . '" onkeyup="calculateTotal(' . $values["id"] . ')"  onblur="updateSession(' . $values["id"] . ')" style="width: 100%;text-align: center;" ' . $discountDisable . '/></td>';
			$productTotal = $values["product_quantity"] * $values["product_price"];
			if ($values["product_discount"] != "") {
				$lastValue = substr($values["product_discount"], -1);
				if ($lastValue == "%") {
					$productDiscount = $productTotal * (substr($values["product_discount"], 0, -1) / 100);
				} else {
					$productDiscount = $values["product_discount"];
				}
				$productTotal = $productTotal - $productDiscount;
			}
			$output .= '
    		    <td align="right"><span id="productTotal' . $values['id'] . '"> ' . sprintf("%.2f", $productTotal) . '</span></td>
    			<td>
    			    
    			    <div class="btn-group">
                    	<button type="button" class="btn btn-deafult dropdown-toggle" data-toggle="dropdown"style="border: 1px solid gray;">
                    	<i class="glyphicon glyphicon-option-horizontal" style="color: #000cbd;"></i></button>
                    	<ul class="dropdown-menu dropdown-menu-right" style="border: 1px solid gray;min-width: 100%;" role="menu">
                    		<li style="margin-left: 0px;"><a class="btn btn-secondary btn-xs delete" id="' . $values["id"] . '" href="#"><span class="glyphicon glyphicon-trash"></span></a></li>
    			            <li style="margin-left: 0px;"><a class="btn btn-xs previousPriceSingleTs" id="-' . $values["product_id"] . '" href="#"><span class="glyphicon glyphicon-th" style="color: #000cbd;"></span></a></li>
                    	    <li style="margin-left: 0px;"><a class="btn btn-xs productSpecification" id="-' . $values["product_id"] . '" href="#"><span class="glyphicon glyphicon-check" style="color: #000cbd;"></span></a></li>
                    	</ul>
                    </div>
    			</td>
    			
    		</tr>';
			$total_price = $total_price + $productTotal;
			$total_item = $total_item + 1;
			$totalProductDiscount = $totalProductDiscount + $productDiscount;
		}
		$output .= '<tr><td style="height: 41px;" colspan="4"></td></tr><tr><td colspan="4"></td></tr>
    	<tr style="display:none;">  
            <td colspan="6" align="right">Total
            <br>Product Discount</td>  
            <td align="right"><span class="totalAmount">' . sprintf("%.2f", $total_price) . '</span>
            <br><span class="totalProductDiscount" style="width: 100%;text-align: center;">' . $totalProductDiscount . '</span></td>  
            <td></td>  
        </tr>
    	<tr style="display:none;">  
            <td colspan="6" align="right">Sales Discount
            <br>Total Discount</td>
            <td align="right"><input type="text" id="salesDiscount" style="width:100%;text-align: right;" onkeyup="calculateTotalDiscount()" value="0"/>
            <br><span class="totalDiscount" style="width: 100%;text-align: center;">' . $totalProductDiscount . '</span></td>  
            <td></td>  
        </tr>
        <tr style="display:none;">
            <td colspan="6" align="right">Grand Total</td>
            <td align="right"><span class="grandTotal" style="width: 100%;">' . sprintf("%.2f", $total_price) . '</span></td>  
            <td></td>
        </tr>
        <tr style="display:none;">
            <td colspan="6" align="right">Payment Method</td>
            <td align="right"><select id="paymentMethod" name="paymentMethod">
                <option value="Cash" selected>Cash</option>
            </select></td>  
            <td></td>
        </tr>
        <tr style="display:none;">
            <td colspan="6" align="right">paid</td>
            <td align="right"><input type="text" id="paid" name="paid" style="width:100%;text-align: right;" /></td>  
            <td></td>
        </tr>
        <tr style="display:none;">
            <td colspan="6" align="right">VAT</td>
            <td align="right"><input type="text" id="vat" name="vat" style="width:100%;text-align: right;" /></td>  
            <td></td>
        </tr>
        <tr style="display:none;">
            <td colspan="6" align="right">AITnt</td>
            <td align="right"><input type="text" id="ait" name="ait" style="width:100%;text-align: right;" /></td>  
            <td></td>
        </tr>
        <tr style="display:none;">
            <td colspan="6" align="right">Carring Cost</td>
            <td align="right"><input type="text" id="carringCost" name="carringCost" style="width:100%;text-align: right;" /></td>  
            <td></td>
        </tr>
    	';
	} else {
		$output .= '
        <tr>
        	<td colspan="5" align="center">
        		Your Cart is Empty!
        	</td>
        </tr>
        ';
	}
	$output .= '</table></div>';
	$data = array(
		'cart_details'		=>	$output,
		'total_price'		=>	'&#2547;' . sprintf("%.2f", $total_price),
		'total_item'		=>	$total_item
	);
	echo json_encode($data);
} else if (isset($_GET['type'])) {
	$type = $_GET['type'];
	if ($_GET['sortData'] == "0,0") {
		$sql = "SELECT tbl_sales.id, tbl_sales.salesOrderNo, tbl_sales.salesDate, tbl_sales.type, tbl_party.partyName, tbl_sales.grandTotal, tbl_users.fname, tbl_users.username, tbl_sales.totalDiscount, tbl_sales.paidAmount, tbl_sales.totalAmount, CONCAT('<ul>', GROUP_CONCAT(CONCAT('<li>', tbl_products.productName,' - ',tbl_products.productCode, ' (',tbl_sales_products.quantity,')') SEPARATOR '</li>'), '</li></ul>') AS salesProducts
                FROM tbl_sales 
                LEFT OUTER JOIN tbl_party ON tbl_sales.tbl_customerId = tbl_party.id AND tbl_party.deleted='No'
                LEFT OUTER JOIN tbl_users ON tbl_sales.createdBy = tbl_users.id AND tbl_users.deleted='No'
                LEFT OUTER JOIN tbl_sales_products ON tbl_sales_products.tbl_salesId = tbl_sales.id AND tbl_sales_products.deleted = 'No'
                LEFT OUTER JOIN tbl_products ON tbl_sales_products.tbl_productsId = tbl_products.id
                WHERE tbl_sales.type = 'TS' AND tbl_sales.deleted='No'
                GROUP BY tbl_sales.id
                ORDER BY id DESC";
	} else if (isset($_GET['customerId'])) {
		$customerId = $_GET['customerId'];
		$sql = "SELECT tbl_sales.id, tbl_sales.salesOrderNo, tbl_sales.salesDate, tbl_sales.type, tbl_party.partyName, tbl_sales.grandTotal, tbl_users.fname, tbl_users.username, tbl_sales.totalDiscount, tbl_sales.paidAmount, tbl_sales.totalAmount, CONCAT('<ul>', GROUP_CONCAT(CONCAT('<li>', tbl_products.productName,' - ',tbl_products.productCode, ' (',tbl_sales_products.quantity,')') SEPARATOR '</li>'), '</li></ul>') AS salesProducts
                FROM tbl_sales 
                LEFT OUTER JOIN tbl_party ON tbl_sales.tbl_customerId = tbl_party.id AND tbl_party.deleted='No'
                LEFT OUTER JOIN tbl_users ON tbl_sales.createdBy = tbl_users.id AND tbl_users.deleted='No'
                LEFT OUTER JOIN tbl_sales_products ON tbl_sales_products.tbl_salesId = tbl_sales.id AND tbl_sales_products.deleted = 'No'
                LEFT OUTER JOIN tbl_products ON tbl_sales_products.tbl_productsId = tbl_products.id
                WHERE tbl_sales.type = 'TS' AND tbl_sales.deleted='No' AND  tbl_sales.tbl_customerId='$customerId'
                GROUP BY tbl_sales.id
                ORDER BY id DESC";
	} else {
		$dates = explode(",", $_GET['sortData']);
		$sql = "SELECT tbl_sales.id, tbl_sales.salesOrderNo, tbl_sales.salesDate, tbl_sales.type, tbl_party.partyName, tbl_sales.grandTotal, tbl_users.fname, tbl_users.username, tbl_sales.totalDiscount, tbl_sales.paidAmount, tbl_sales.totalAmount, CONCAT('<ul>', GROUP_CONCAT(CONCAT('<li>', tbl_products.productName,' - ',tbl_products.productCode, ' (',tbl_sales_products.quantity,')') SEPARATOR '</li>'), '</li></ul>') AS salesProducts
                FROM tbl_sales 
                LEFT OUTER JOIN tbl_party ON tbl_sales.tbl_customerId = tbl_party.id AND tbl_party.deleted='No'
                LEFT OUTER JOIN tbl_users ON tbl_sales.createdBy = tbl_users.id AND tbl_users.deleted='No'
                LEFT OUTER JOIN tbl_sales_products ON tbl_sales_products.tbl_salesId = tbl_sales.id AND tbl_sales_products.deleted = 'No'
                LEFT OUTER JOIN tbl_products ON tbl_sales_products.tbl_productsId = tbl_products.id
                WHERE tbl_sales.type = 'TS' AND tbl_sales.deleted='No' AND tbl_sales.salesDate BETWEEN '" . $dates[0] . "' AND '" . $dates[1] . "' 
                GROUP BY tbl_sales.id
                ORDER BY id DESC";
	}
	$result = $conn->query($sql);
	$i = 1;
	$output = array('data' => array());
	while ($row = $result->fetch_array()) {
		$salesId = $row['id'];
		$button = '	<div class="btn-group">
						<button type="button" class="btn btn-warning dropdown-toggle" data-toggle="dropdown">
						<i class="fa fa-gear tiny-icon"></i>  <span class="caret"></span></button>
						<ul class="dropdown-menu dropdown-menu-right" style="border: 1px solid gray;" role="menu">
							<li><a href="#"  onclick="salesReport(' . $salesId . ',\'FS\')" ><i class="fa fa-print tiny-icon"></i>View Details</a></li>';
		$button .=  '<li><a href="sale-return.php?salesId=' . $salesId . '&salesType=FS"><i class="fa fa-edit tiny-icon"></i>FS Return</a></li>';
		if (strtolower($_SESSION['userType']) == 'super admin') {

			$button .=  '<li><a href="#" onclick="deleteSales(' . $salesId . ')"><i class="fa fa-edit tiny-icon"></i>Delete</a></li>';
		}
		$button .= '</ul></div>';
		$output['data'][] = array(
			$i++,
			$row['salesOrderNo'],
			$row['partyName'] . '<br>' . $row['salesDate'],
			$row['fname'],
			$row['salesProducts'],
			'Total: ' . $row['totalAmount'] . '<br>Discount: ' . $row['totalDiscount'] . '<br>Grand: ' . $row['grandTotal'] . '<br>Paid: ' . $row['paidAmount'],
			$button
		);
	} // /while 
	echo json_encode($output);
} else {
	if ($_GET['sortData'] == "0,0") {
		$sql = "SELECT tbl_temporary_sale.id, tbl_temporary_sale.tsNo, tbl_temporary_sale.tSalesDate, tbl_party.partyName, tbl_party.partyPhone, CONCAT('<ul>', GROUP_CONCAT(CONCAT('<li>',tbl_products.productName, ' - ', tbl_products.productCode,' (',tbl_tsalesproducts.quantity,' ',tbl_units.unitName,' - ',tbl_tsalesproducts.status,')') SEPARATOR '</li>') ,'</li></ul>') AS salesProducts, tbl_users.fname, tbl_users.username
                FROM tbl_temporary_sale
                LEFT OUTER JOIN tbl_tsalesproducts ON tbl_tsalesproducts.tbl_tSalesId = tbl_temporary_sale.id AND tbl_tsalesproducts.deleted = 'No'
                LEFT OUTER JOIN tbl_products ON tbl_tsalesproducts.tbl_productsId = tbl_products.id
                LEFT OUTER JOIN tbl_party ON tbl_temporary_sale.tbl_customerId = tbl_party.id
                LEFT OUTER JOIN tbl_users ON tbl_temporary_sale.tbl_userId = tbl_users.id
                LEFT OUTER JOIN tbl_units ON tbl_products.units = tbl_units.id
                WHERE tbl_temporary_sale.deleted = 'No'
                GROUP BY tbl_temporary_sale.id
                ORDER BY id DESC";
	} else if (isset($_GET['sortData'])) {
		$dates = explode(",", $_GET['sortData']);
		$sql = "SELECT tbl_temporary_sale.id, tbl_temporary_sale.tsNo, tbl_temporary_sale.tSalesDate, tbl_party.partyName, tbl_party.partyPhone, CONCAT('<ul>', GROUP_CONCAT(CONCAT('<li>',tbl_products.productName, ' - ', tbl_products.productCode,' (',tbl_tsalesproducts.quantity,' ',tbl_units.unitName,' - ',tbl_tsalesproducts.status,')') SEPARATOR '</li>') ,'</li></ul>') AS salesProducts, tbl_users.fname, tbl_users.username
                FROM tbl_temporary_sale
                LEFT OUTER JOIN tbl_tsalesproducts ON tbl_tsalesproducts.tbl_tSalesId = tbl_temporary_sale.id AND tbl_tsalesproducts.deleted = 'No'
                LEFT OUTER JOIN tbl_products ON tbl_tsalesproducts.tbl_productsId = tbl_products.id
                LEFT OUTER JOIN tbl_party ON tbl_temporary_sale.tbl_customerId = tbl_party.id
                LEFT OUTER JOIN tbl_users ON tbl_temporary_sale.tbl_userId = tbl_users.id
                LEFT OUTER JOIN tbl_units ON tbl_products.units = tbl_units.id
                WHERE tbl_temporary_sale.deleted = 'No' AND tbl_temporary_sale.tSalesDate BETWEEN '" . $dates[0] . "' AND '" . $dates[1] . "'
                GROUP BY tbl_temporary_sale.id
                ORDER BY id DESC";
	} else if (isset($_GET['customerId'])) {
		$customerId = $_GET['customerId'];
		$sql = "SELECT tbl_temporary_sale.id, tbl_temporary_sale.tbl_customerId,tbl_temporary_sale.tsNo, tbl_temporary_sale.tSalesDate, tbl_party.partyName, tbl_party.partyPhone, CONCAT('<ul>', GROUP_CONCAT(CONCAT('<li>',tbl_products.productName, ' - ', tbl_products.productCode,' (',tbl_tsalesproducts.quantity,' ',tbl_units.unitName,' - ',tbl_tsalesproducts.status,')') SEPARATOR '</li>') ,'</li></ul>') AS salesProducts, tbl_users.fname, tbl_users.username
                FROM tbl_temporary_sale
                LEFT OUTER JOIN tbl_tsalesproducts ON tbl_tsalesproducts.tbl_tSalesId = tbl_temporary_sale.id AND tbl_tsalesproducts.deleted = 'No'
                LEFT OUTER JOIN tbl_products ON tbl_tsalesproducts.tbl_productsId = tbl_products.id
                LEFT OUTER JOIN tbl_party ON tbl_temporary_sale.tbl_customerId = tbl_party.id
                LEFT OUTER JOIN tbl_users ON tbl_temporary_sale.tbl_userId = tbl_users.id
                LEFT OUTER JOIN tbl_units ON tbl_products.units = tbl_units.id
                WHERE tbl_temporary_sale.deleted = 'No' AND tbl_temporary_sale.tbl_customerId='$customerId'
                GROUP BY tbl_temporary_sale.id ORDER BY id DESC";
	}
	$result = $conn->query($sql);
	$i = 1;
	$output = array('data' => array());
	while ($row = $result->fetch_array()) {
		$salesId = $row['id'];
		$button = '	<div class="btn-group">
						<button type="button" class="btn btn-warning dropdown-toggle" data-toggle="dropdown">
						<i class="fa fa-gear tiny-icon"></i>  <span class="caret"></span></button>
						<ul class="dropdown-menu dropdown-menu-right" style="border: 1px solid gray;" role="menu">
							<li><a href="#" onclick="salesReport(' . $salesId . ',\'TS\')"><i class="fa fa-print tiny-icon"></i>View Details</a></li>';
		if (strtolower($_SESSION['userType']) == "admin coordinator" || strtolower($_SESSION['userType']) == 'super admin') {
			$button .=  '<li><a href="#" onclick="deleteSales(' . $salesId . ')"><i class="fa fa-edit tiny-icon"></i>Delete</a></li>';
		}
		$button .= '</ul></div>';
		$output['data'][] = array(
			$i++,
			$row['tsNo'],
			$row['tSalesDate'],
			$row['partyName'],
			$row['fname'],
			$row['salesProducts'],
			$button
		);
	} // /while 
	echo json_encode($output);
}
