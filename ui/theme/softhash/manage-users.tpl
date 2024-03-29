{include file="sections/header.tpl"}

<div class="ibox float-e-margins">
    <div class="ibox-title">
        <h5>Add User </h5>

    </div>
    <div class="ibox-content">
        <div class="row">
            <div class="col-md-6">
                <div class="form-horizontal">
                    <div class="form-group">
                        <label for="name" class="col-sm-2 control-label">Name</label>
                        <div class="col-sm-10">
                            <input type="text" class="form-control" id="name">
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="email" class="col-sm-2 control-label">Email</label>
                        <div class="col-sm-10">
                            <input type="email" class="form-control" id="email">
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="password" class="col-sm-2 control-label">Password</label>
                        <div class="col-sm-10">
                            <input type="password" class="form-control" id="password">
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="col-sm-offset-2 col-sm-10">
                            <button type="submit" id="submit" class="btn btn-primary"><i class="fa fa-check"></i> Submit</button>
                        </div>
                    </div>

                </div>
            </div>

            <div class="col-md-6">
                <div class="form-horizontal">
                    <div class="form-group">
                        <label for="inputEmail3" class="col-sm-2 control-label">Active</label>
                        <div class="col-sm-10">
                            <div class="btn-group" data-toggle="buttons">
                                <label class="btn btn-primary btn-outline active">
                                    <input type="radio" name="options" id="option1" autocomplete="off" checked> Yes
                                </label>
                                <label class="btn btn-primary btn-outline">
                                    <input type="radio" name="options" id="option2" autocomplete="off"> No
                                </label>

                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="inputEmail3" class="col-sm-2 control-label">Access Level</label>
                        <div class="col-sm-10">
                            <select class="form-control">
                                <option>Full Access</option>
                                <option>Sales</option>

                            </select>
                        </div>
                    </div>




                </div>
            </div>
        </div>


        <!-- /table-responsive -->





    </div>
</div>


<div class="ibox float-e-margins">
    <div class="ibox-title">
        <h5 id="txtloading">Loading Users.... </h5>

    </div>
    <div class="ibox-content">
        <div class="progress">
            <div class="progress-bar" data-transitiongoal="75"></div>
        </div>

        <div id="result">

        </div>

    </div>
</div>
{include file="sections/footer.tpl"}