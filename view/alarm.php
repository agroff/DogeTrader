<h3>
    Alarms
</h3>

<div class="row">
    <div class="large-6 columns">
        <input type="text" placeholder="Satoshi" id="alarm-satoshi">

    </div>
    <div class="large-6 columns">
        <select class="custom pad" id="alarm-id">
            <option value="">Select Sound...</option>
            <?php foreach ($alarms as $alarm): ?>
                <option value="<?php o($alarm["id"]); ?>">
                    <?php o($alarm["name"]); ?>
                </option>
            <?php endforeach; ?>
        </select>

    </div>
    <div class="large-6 columns">
        <button class="small radius button" id="new-alarm" type="button">Add Alarm</button>
    </div>
</div>
<div class="row">
    <div class="large-9 columns">
        <div class="panel tablePanel">

            <div class="row smallTable strong">

                <div class="small-10 columns">
                    Pending Alarms
                </div>
                <div class="small-8 columns">
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
    <div class="large-9 columns">

        <div class="panel tablePanel">

            <div class="row smallTable strong">

                <div class="small-10 columns">
                    Triggered Alarms
                </div>
                <div class="small-8 columns">
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