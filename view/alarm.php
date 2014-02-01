<h3>
    Alarms
</h3>

<div class="row">
    <div class="large-4 columns">

        <div class="row">
            <div class="small-18 columns">

                <select class="custom pad" id="alarm-id">
                    <option value="">Select Sound...</option>
                    <?php foreach ($alarms as $alarm): ?>
                        <option value="<?php o($alarm["id"]); ?>">
                            <?php o($alarm["name"]); ?>
                        </option>
                    <?php endforeach; ?>
                </select>

            </div>
        </div>

        <div class="row">
            <div class="small-18 columns">

                <input type="text" placeholder="Satoshi" id="alarm-satoshi">

            </div>
        </div>
        <div class="row">

            <div class="small-18 columns">

                <button class="small radius button small-18" id="new-alarm" type="button">Add Alarm</button>

            </div>
        </div>


    </div>
    <div class="large-7 columns">

        <div class="panel tablePanel">

            <div class="row smallTable strong">

                <div class="small-11 columns">
                    Pending Alarms
                </div>
                <div class="small-7 columns">
                    Satoshi
                </div>
            </div>
            <div id="alarmList">
                <div class="small center">
                    No Alarms.
                </div>

            </div>

        </div>


    </div>
    <div class="large-7 columns">

        <div class="panel tablePanel">

            <div class="row smallTable strong">

                <div class="small-11 columns">
                    Triggered Alarms
                </div>
                <div class="small-7 columns">
                    Satoshi
                </div>
            </div>
            <div id="triggeredAlarmList">
                <div class="small center">
                    No Alarms.
                </div>

            </div>


        </div>

    </div>
</div>
<div class="row">
    <div class="large-9 columns">

    </div>
    <div class="large-9 columns">


    </div>
</div>