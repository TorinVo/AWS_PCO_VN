<div id="set_<?=$idSet?>" class="tab-pane fade set-route">
    <div class="row">
        <div class="col-lg-4 col-md-4">
            <div class="set-name" style="margin-top: 10px">
                <label for="name_<?=$idSet?>">Set Name</label>
                <input class="input-name" class="" type="text" name="name_<?=$idSet?>" placeholder="Set <?=$idSet?>">
                <button type="button" value="<?=$idSet?>" onclick="ActiveSet(this)" class="btn btn-info">Set active</button>
            </div>
            <div class="inner-addon left-addon" style="margin-top: 10px">
                <i class="glyphicon glyphicon-search"></i>
                <input onkeyup="Route_Active.Search(this)" id="searchRoute_<?=$idSet?>" type="text"  placeholder="Search" class="form-control Search">
            </div>
        </div>
        <div class="col-lg-offset-2 col-md-offset-2 col-lg-6 col-md-6 right">
            <button class="btn btn-danger" style="margin-top: 10px"><i class="fa fa-ban"></i></button><br>
            <div style="margin-top: 10px">
                <button class="btn btn-primary" onclick="NewRoute()">New route</button>
                <button class="btn btn-primary">Select all</button>
                <button class="btn btn-primary">Actions</button>
            </div>
        </div>
    </div>
    <div class="row content">
        <div class="col-md-7 col-lg-7">
            <table class="table table-hover table-striped tbl_routes_<?=$idSet?>">
                <thead>
                    <tr>
                        <th><div class="custom-checkbox">
                            <input type="checkbox" name="select_all_<?=$idSet?>" id="allRoute_<?=$idSet?>" onchange="Route_Active.CheckAll(this)">
                            <label for="allRoute_<?=$idSet?>" style="font-weight: normal;"></label>
                        </div></t       h>
                        <th>Route No.</th>
                        <th>Route Name</th>
                        <th>Services</th>
                        <th>Coverage Area</th>
                        <th>Covered Zip</th>
                        <th>Default Technician</th>
                    </tr>
                </thead>
                <tbody></tbody>
            </table>
            <p class="entries"><span id="item_count_<?=$idSet?>"></span> entries selected</p>
        </div>
        <div class="col-md-5 col-lg-5">
            <p><strong>Showing </strong><strong id="route-name_<?=$idSet?>"></strong></p>
            <div class="row">
                <p style="margin-left: 15px"><strong>Filters</strong></p>
                <div class="col-md-8 col-lg-8">
                    <div class="row">
                        <select class="filter" name="" id="" style="margin-left: 15px">
                            <option value="">All Territories</option>
                        </select>
                        <select class="filter" name="" id="">
                            <option value="">Recurring Jobs</option>
                        </select>
                        <select class="filter" name="" id="">
                            <option value="">Today's Jobs</option>
                        </select>
                    </div>
                    <div id="map_<?=$idSet?>" style="height: 400px;margin-top: 15px"></div>
                </div>
                <div class="col-md-4 col-lg-4">
                    <select name="" id="" class="filter">
                        <option value="">Action On Selected</option>
                    </select>
                    <label class="form-label">Registered Territories</label>
                    <ul class="trtr-list">
                        <div class="custom-checkbox">
                            <input type="checkbox" onchange="" id="showAllType">
                            <label for="showAllType" style="font-weight: normal;">Territory 1</label>
                        </div>
                        <li>
                            <div class="custom-checkbox">
                                <input type="checkbox" onchange="" class="SubFilterType" id="111222" value="111222">
                                <label for="111222" style="font-weight: normal;">Territory 2</label>
                            </div>
                        </li>
                        <li>            
                            <div class="custom-checkbox">
                                <input type="checkbox" onchange="" class="SubFilterType" id="Customer Type 1" value="Customer Type 1">
                                <label for="Customer Type 1" style="font-weight: normal;">Territory 3</label>
                            </div>
                        </li>
                        <li>
                            <div class="custom-checkbox">
                                <input type="checkbox" onchange="" class="SubFilterType" id="lzlz" value="lzlz">
                                <label for="lzlz" style="font-weight: normal;">Territory 4</label>
                            </div>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>
<?php echo $jsLoad; ?>