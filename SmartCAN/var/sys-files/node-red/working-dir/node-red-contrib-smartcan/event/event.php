<script type="text/javascript">
    RED.nodes.registerType('event',{
        category: 'smartcan',
        color: '#EBEDEC',
        defaults: {
            scinput: {value: "1"},
            name: {value: ""},
            topic: {value:""}
        },
        inputs:1,
        outputs:2,
        icon: "event.png",
        label: function() {
            return this.name||"event";
        }
    });
</script>

<script type="text/x-red" data-template-name="event">
    <div class="form-row">
        <label for="node-input-scinput"><i class="fa fa-repeat"></i>SmartCAN Input</label>
			<select id="cars" id="node-input-scinput" placeholder="1000"  style="width: 150px;">
				<option value="volvo">Volvo3</option>
				<option value="saab">Saab</option>
				<option value="fiat">Fiat</option>
				<option value="audi">Audi</option>
			</select>
			<php echo("Test"); ?>
    </div>
    <div class="form-row">
        <label for="node-input-topic"><i class="fa fa-tasks"></i>Topic</label>
        <input type="text" id="node-input-topic" placeholder="Topic"  style="width: 150px;">
    </div>
    <div class="form-row">
        <label for="node-input-name"><i class="fa fa-tag"></i>Name</label>
        <input type="text" id="node-input-name" placeholder="Name"  style="width: 150px;">
    </div>
</script>

<script type="text/x-red" data-help-name="event">
    <p>
        Select Event on input to trigger flow<br/>
    </p>
</script>