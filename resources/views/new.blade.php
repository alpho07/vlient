@extends('layouts.app')

@section('content')
<div class="container">
    <!--=== Page Header ===-->
    @include('partials.mini-header')
    <!-- /Page Header -->

    <!--=== Page Content ===-->
    <!--=== Full Size Inputs ===-->
    <form class="form-horizontal row-border" action="#">
        <div class="row">

            <div class="col-md-12">
                <div class="widget box">
                    <div class="widget-header">
                        <h4><i class="icon-reorder"></i> Sample General Info</h4>
                    </div>
                    <div class="widget-content">

                        <div class="alert alert-info fade in">
                            <i class="icon-remove close" data-dismiss="alert"></i>
                            Please enter full sample and personal details below
                        </div>
                        <div class="form-group">                            
                            <div class="col-md-12">
                                <div class="row">
                                    <div class="col-md-3">
                                        <select class="form-control" id="culture-time">
                                            <option value="" selected="selected">-Priority-</option>
                                            <option value="Low">Low</option>
                                            <option value="High">High</option>
                                        </select>
                                    </div>
                                    <div class="col-md-3">
                                        <select class="form-control" id="culture-time">
                                            <option value="" selected="selected">-Currency-</option>
                                            <option value="KES">KES</option>
                                            <option value="USD">USD</option>
                                        </select>
                                    </div>
                                    <div class="col-md-3">
                                        <select class="form-control" id="culture-time">
                                            <option value="" selected="selected">-Client Type-</option>
                                            <option value="A">A</option>
                                            <option value="B">B</option>
                                            <option value="C">C</option>
                                            <option value="D">D</option>
                                            <option value="E">E</option>
                                        </select>
                                    </div>
                                    <div class="col-md-3">
                                        <input type="text" name="regular" class="form-control" readonly value="NDQ">
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>
        <!--Forms -->

        <div class="row">
            <div class="col-md-12">
                <div class="widget box">
                    <div class="widget-header">
                        <h4><i class="icon-reorder"></i> Personal Information</h4>
                    </div>
                    <div class="widget-content">
                        <div class="form-group">
                            <div class="col-md-4">
                                <label class="radio">
                                    <input type="text" name="clientname" class="form-control"  placeholder="CLIENT NAME">
                                </label>
                                <label class="radio">
                                    <input type="text" name="contactname" class="form-control"  placeholder="CONTACT NAME">
                                </label>

                            </div>
                            <div class="col-md-4">
                                <label class="radio">
                                    <input type="text" name="clientemail" class="form-control"  placeholder="CLIENT EMAIL">
                                </label>
                                <label class="radio">
                                    <input type="text" name="contacttel" class="form-control"  placeholder="CONTACT TELEPHONE">
                                </label>

                            </div>
                            <div class="col-md-4">
                                <label class="radio">
                                    <textarea  cols="5" name="clientaddress" class="form-control" placeholder="CLIENT ADDRESS"></textarea>
                                </label>


                            </div>
                        </div>                    
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-12">
                <div class="widget box">
                    <div class="widget-header">
                        <h4><i class="icon-reorder"></i> Product Information</h4>
                    </div>
                    <div class="widget-content">
                        <div class="form-group">
                            <div class="col-md-3">
                                <label class="radio">
                                    <textarea  cols="5" name="productname" class="form-control" placeholder="PRODUCT NAME"></textarea>
                                </label>
                                <label class="radio">
                                    <input type="text" name="batchno" class="form-control"  placeholder="BATCH NO">
                                </label>
                                <label class="radio">
                                    <input type="text" name="mfgdate" class="form-control"  placeholder="MANUFACTURE DATE">
                                </label>
                                <label class="radio">
                                    <input type="text" name="expirydate" class="form-control"  placeholder="EXPIRY DATE">
                                </label>

                            </div>
                            <div class="col-md-3">
                                <label class="radio">
                                    <textarea  cols="5" name="activeingredient" class="form-control" placeholder="ACTIVE INGREDIENT"></textarea>
                                </label>
                                <label class="radio">
                                    <select class="form-control" id="culture-time">
                                        <option value="" selected="selected">-Dosage Form-</option>
                                        <option value="Tablets">Tablets</option>
                                        <option value="Tablets">Tablets</option>
                                        <option value="Vials">Vials</option>
                                        <option value="Bottles">Bottles</option>
                                        <option value="Sachets">Sachets</option>
                                    </select>

                                </label>


                            </div>
                            <div class="col-md-3">
                                <label class="radio">
                                    <textarea  cols="5" name="labelclaim" class="form-control" placeholder="LABELCLAIM"></textarea>
                                </label>
                                <label class="radio">
                                    <input type="text" name="quantity" class="form-control"  placeholder="QUANTITY SUBMITTED">

                                </label>                              

                            </div>
                            <div class="col-md-3">
                                <label class="radio">
                                    <textarea  cols="5" name="presentation" class="form-control" placeholder="PRESENTATION"></textarea>
                                </label>
                                <label class="radio">
                                    <select class="form-control" id="culture-time">
                                        <option value="" selected="selected">-Unit-</option>
                                        <option value="Tablets">Tablets</option>
                                        <option value="Tablets">Tablets</option>
                                        <option value="Vials">Vials</option>
                                        <option value="Bottles">Bottles</option>
                                        <option value="Sachets">Sachets</option>
                                    </select>
                                </label>


                            </div>

                        </div> 

                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-12">
                <div class="widget box">
                    <div class="widget-header">
                        <h4><i class="icon-reorder"></i> Tests Request per department</h4>
                    </div>
                    <div class="widget-content">
                        <div class="form-group">
                            <div class="col-md-4">
                                <label class="radio">
                                    <strong><u>Wet-Chemistry</u></strong> 
                                </label>
                                <label class="radio">
                                    <input type="checkbox" class="uniform" value=""> Identification
                                </label>
                                <label class="radio">
                                    <input type="checkbox" class="uniform" value=""> Uniformity of weight
                                </label>
                                <label class="radio">
                                    <input type="checkbox" class="uniform" value=""> Assay
                                </label>
                                <label class="radio">
                                    <input type="checkbox" class="uniform" value=""> Disintegration
                                </label>
                                <label class="radio">
                                    <input type="checkbox" class="uniform" value=""> Friability
                                </label>
                                <label class="radio">
                                    <input type="checkbox" class="uniform" value=""> Alkalinity/Acidity
                                </label>



                            </div>
                            <div class="col-md-4">
                                <label class="radio">
                                    <strong><u> Microbial</u></strong>
                                </label>
                                <label class="radio">
                                    <input type="checkbox" class="uniform" value=""> Sterility
                                </label>
                                <label class="radio">
                                    <input type="checkbox" class="uniform" value=""> Microbial Load
                                </label>
                                <label class="radio">
                                    <input type="checkbox" class="uniform" value=""> Microbial Assay
                                </label>
                                <label class="radio">
                                    <input type="checkbox" class="uniform" value=""> Bacterial Endotoxin
                                </label>
                                <label class="radio">
                                    <input type="checkbox" class="uniform" value=""> Preservative Efficacy
                                </label>



                            </div>
                            <div class="col-md-4">
                                <label class="radio">
                                    <strong><u> Mediacal Devices</u></strong>
                                </label>
                                <label class="radio">
                                    <input type="checkbox" class="uniform" value=""> Package Integrity
                                </label>
                                <label class="radio">
                                    <input type="checkbox" class="uniform" value=""> Glove Test
                                </label>
                                <label class="radio">
                                    <input type="checkbox" class="uniform" value=""> Condom Test
                                </label>
                                <label class="radio">
                                    <input type="checkbox" class="uniform" value=""> Visual Leak Test
                                </label>
                                <label class="radio">
                                    <input type="checkbox" class="uniform" value=""> Preservative Efficacy
                                </label>
                                <label class="radio">
                                    <input type="checkbox" class="uniform" value=""> Wet package Integrity Test
                                </label>
                                <label class="radio">
                                    <input type="checkbox" class="uniform" value=""> ASTM Method for water Test
                                </label>


                            </div>


                        </div> 

                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12">
                <div class="widget box">
                    <div class="widget-header">
                        <h4><i class="icon-reorder"></i> Product Origin</h4>
                    </div>
                    <div class="widget-content">
                        <div class="form-group">
                            <div class="col-md-4">

                                <label class="radio">
                                    <input type="text" name="mfgname" class="form-control"  placeholder="MANUFACTURER NAME">
                                </label>


                            </div>

                            <div class="col-md-4">
                                <label class="radio">
                                    <textarea  cols="5" name="mfgaddress" class="form-control" placeholder="MANUFACTURER ADDRESS"></textarea>
                                </label>


                            </div>
                            <div class="col-md-4">

                                <label class="radio">
                                    <select class="form-control" id="culture-time">
                                        <option value="" selected="selected">-Country of Origin-</option>
                                        <option value="Kenya">Kenya</option>
                                        <option value="India">India</option>
                                        <option value="USA">USA</option>
                                        <option value="Germany">Germany</option>
                                        <option value="Britain">Britain</option>
                                    </select>
                                </label>


                            </div>

                        </div> 

                    </div>
                </div>
            </div>

        </div>
        <div class="row">
             <div class="form-group">
            <button type="submit" class="btn btn-success btn-lg">SUBMIT ANALYSIS REQUEST</button>
             </div>
        </div>
    </form>
</div>

<!-- /Page Content -->
</div>
<!-- /.container -->
</div>
@endsection
