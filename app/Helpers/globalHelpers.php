<?php  
use App\Quotations;

function getQuotationId(){
			
	//Get no. of quotations in table
	$no_of_quotations = Quotations::count();

	//Increment no. of quotations by 1
	$quotation_serial = $no_of_quotations[0]["count"] + 1;

	//Pad quotation no with two leading zeros
	$serial = sprintf('%02s', $quotation_serial);

	//Generate quotation number
	$quotation_no = 'Q-'.date('hms')."-".$serial;

	return $quotation_no;
}


function getQuotationNo($c, $q){

	//Condition checks whether quotation_no has been selected in generate quotation view
	if(!empty($q) && $q != 'New'){
		$quotation_no = $q;
	}
	else if(empty($q) || $q == 'New'){

	//Get No. of Quotations
		$no_of_quotations = Quotations::where('client_number',  $c)->get()->count();

		//Increment no. of quotations by 1
		$quotation_serial = $no_of_quotations[0]["count"] + 1;

		//Pad quotation no with two leading zeros
		$serial = sprintf('%02s', $quotation_serial);

		//Generate quotation number
		$quotation_no = 'NDQ-'.$c."-".date('y-m-d-hms')."-Q-".$serial;
	}

	return $quotation_no;
}


?>