<div id="wrap-close-overlay">
    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12 title-left-close-overlay">
        <div class="DivParent">
            <div class="DivWhichNeedToBeVerticallyAligned">Add Routes</div>
            <div class="DivHelper"></div>
        </div>
    </div>
    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12 title-right-close-overlay">
        <button class="btn btn-sm btn-primary" onclick="Route_Active.SaveRoute()">Save Route</button>
        <button type="button" class="btn btn-sm btn-primary" onclick="Js_Top.closeNav()">X</button>
    </div>
</div>
<div id="overlay-content" class="overlay-content">
    <div class="content" style="padding: 40px;background-color: lightgray;padding-bottom: 200px">
        <form id="newRoute">
            <div class="row">
                <h4><strong>Route Information</strong></h4>
                <div class="row">
                    <div class="col-lg-3 text-right">
                        <p class="cap-data"><strong>Route No</strong></p>
                    </div>
                    <div class="col-lg-3 text-left">
                        <input type="text" name="route_no" onkeyup="CheckExistingRoute(this)" style="width: 100%">
                    </div>
                    <p class="no-validate hidden" style="color: red;font-style: italic;">The route number is already exist!</p>
                </div>
                <div class="pad-item"></div>
                <div class="row">
                    <div class="col-lg-3 text-right">
                        <p class="cap-data"><strong>Route Name</strong></p>
                    </div>
                    <div class="col-lg-3 text-left">
                        <input type="text" name="route_name" value="1" style="width: 100%">
                    </div>
                </div>
                <div class="pad-item"></div>
                <div class="row">
                    <div class="col-lg-3 text-right">
                        <p class="cap-data"><strong>Total Active Services</strong></p>
                    </div>
                    <div class="col-lg-3 text-right">
                        0
                    </div>
                    <div class="col-lg-6 text-left">
                        <button class="btn btn-primary" onclick="EmptyItem()">List</button>
                    </div>
                </div>
                <div class="pad-item"></div>
                <div class="row">
                    <div class="col-lg-3 text-right">
                        <p class="cap-data"><strong>Total Inactive Services</strong></p>
                    </div>
                    <div class="col-lg-3 text-right">
                        0
                    </div>
                    <div class="col-lg-6 text-left">
                        <button class="btn btn-primary" onclick="EmptyItem()">List</button>
                    </div>
                </div>
                <div class="pad-item"></div>
                <div class="row">
                    <div class="col-lg-3 text-right">
                        <p class="cap-data"><strong>Map Area Covered</strong></p>
                        <p class="subcript"><strong>Optional. </strong>Draw a geographical area on a map and have all services in that area fall into this route.</p>
                    </div>
                    <div class="col-lg-3 text-right">
                        0
                    </div>
                    <div class="col-lg-6 text-left">
                        <button class="btn btn-primary" onclick="EmptyItem()">Map</button>
                    </div>
                </div>
                <div class="pad-item"></div>
                <div class="row">
                    <div class="col-lg-3 text-right">
                        <p class="cap-data"><strong>Postal Codes (ZIP) Covered</strong></p>
                        <p class="subcript"><strong>Optional. </strong>Have services covered by the postal code automatically fall into this route. Separate with comma (,).</p>
                    </div>
                    <div class="col-lg-9 text-left">
                        <input name="route_zip" id="tags" value="" />
                    </div>
                </div>
                <div class="pad-item"></div>
                <div class="row">
                    <div class="col-lg-3 text-right">
                        <p class="cap-data"><strong>Default Technician</strong></p>
                        <p class="subcript"><strong>Optional. </strong>New services created under this route will automatically be assigned the selected technician.</p>
                    </div>
                    <div class="col-lg-3 text-left">
                        <select name="technician" id="" style="width: 100%">
                            <?php for ($i = 0; $i < count($technician); $i++) {
                                echo '<option value="'.$technician[$i]->id.'">'.$technician[$i]->name.'</option>';
                            } ?>
                        </select>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>
<script>
    $('#tags').tagsInput();
</script>