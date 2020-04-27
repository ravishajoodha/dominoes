<div class="header">
	<table style="width: 100%;">
		<tr>
			<td style="text-align: left; width: 33%;">
				<a class="action_btn btn-white" title="PLAY" href="index.php">
					<i class="fas fa-arrow-left"></i> BACK
				</a>
			</td>
			<td style="text-align: center;">
				<label id="minutes">00</label>:<label id="seconds">00</label>
			</td>
			<td style="text-align: right; width: 33%;">
				<?php
				if(isset($demo)){
					echo "DEMO &nbsp;&nbsp;";
				}
				else{
					echo "Good Luck ! &nbsp;&nbsp;";
				}
				?>
			</td>
		</tr>
	</table>
</div>



<script>
var minutesLabel = document.getElementById("minutes");
        var secondsLabel = document.getElementById("seconds");
        var totalSeconds = 0;
        setInterval(setTime, 1000);

        function setTime()
        {
            ++totalSeconds;
            secondsLabel.innerHTML = pad(totalSeconds%60);
            minutesLabel.innerHTML = pad(parseInt(totalSeconds/60));
        }

        function pad(val)
        {
            var valString = val + "";
            if(valString.length < 2)
            {
                return "0" + valString;
            }
            else
            {
                return valString;
            }
        }
</script>