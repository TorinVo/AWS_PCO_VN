<div id="wrap-close-overlay">
    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12 title-left-close-overlay">
        <div class="DivParent">
            <div class="DivWhichNeedToBeVerticallyAligned">Edit Routes</div>
            <div class="DivHelper"></div>
        </div>
    </div>
    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12 title-right-close-overlay">
        <button class="btn btn-sm btn-primary">Save Route</button>
        <button class="btn btn-sm btn-primary">Delete Route</button>
        <button type="button" class="btn btn-sm btn-primary" onclick="Js_Top.closeNav()">X</button>
    </div>
</div>
<div id="overlay-content" class="overlay-content">
    <div class="content" style="padding: 40px;background-color: lightgray;padding-bottom: 200px">
        <div class="row">
            <h4><strong>Route Information</strong></h4>
            <div class="row">
                <div class="col-lg-3 text-right">
                    <p class="cap-data"><strong>Route No</strong></p>
                </div>
                <div class="col-lg-3 text-left">
                    <input type="text" value="<?=$route['route_no']?>" style="width: 100%">
                </div>
            </div>
            <div class="pad-item"></div>
            <div class="row">
                <div class="col-lg-3 text-right">
                    <p class="cap-data"><strong>Route Name</strong></p>
                </div>
                <div class="col-lg-3 text-left">
                    <input type="text" value="<?=$route['route_name']?>" style="width: 100%">
                </div>
            </div>
            <div class="pad-item"></div>
            <div class="row">
                <div class="col-lg-3 text-right">
                    <p class="cap-data"><strong>Total Active Services</strong></p>
                </div>
                <div class="col-lg-3 text-right">
                    485
                </div>
                <div class="col-lg-6 text-left">
                    <button class="btn btn-primary">List</button>
                </div>
            </div>
            <div class="pad-item"></div>
            <div class="row">
                <div class="col-lg-3 text-right">
                    <p class="cap-data"><strong>Total Inactive Services</strong></p>
                </div>
                <div class="col-lg-3 text-right">
                    485
                </div>
                <div class="col-lg-6 text-left">
                    <button class="btn btn-primary">List</button>
                </div>
            </div>
            <div class="pad-item"></div>
            <div class="row">
                <div class="col-lg-3 text-right">
                    <p class="cap-data"><strong>Map Area Covered</strong></p>
                    <p class="subcript"><strong>Optional. </strong>Draw a geographical area on a map and have all services in that area fall into this route.</p>
                </div>
                <div class="col-lg-3 text-right">
                    485
                </div>
                <div class="col-lg-6 text-left">
                    <button class="btn btn-primary">Map</button>
                </div>
            </div>
            <div class="pad-item"></div>
            <div class="row">
                <div class="col-lg-3 text-right">
                    <p class="cap-data"><strong>Postal Codes (ZIP) Covered</strong></p>
                    <p class="subcript"><strong>Optional. </strong>Have services covered by the postal code automatically fall into this route. Separate with comma (,).</p>
                </div>
                <div class="col-lg-9 text-left">
                    <textarea name="" id="" cols="30" rows="3"></textarea>
                </div>
            </div>
            <div class="pad-item"></div>
            <div class="row">
                <div class="col-lg-3 text-right">
                    <p class="cap-data"><strong>Default Technician</strong></p>
                    <p class="subcript"><strong>Optional. </strong>New services created under this route will automatically be assigned the selected technician.</p>
                </div>
                <div class="col-lg-3 text-left">
                    <select name="" id="" style="width: 100%">
                        <?php for ($i = 0; $i < count($technician); $i++) {
                            echo '<option value="'.$technician[$i]->id.'">'.$technician[$i]->name.'</option>';
                        } ?>
                    </select>
                </div>
            </div>
        </div>
    </div>
</div>