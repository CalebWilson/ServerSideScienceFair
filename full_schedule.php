<?php

include "FullSchedule.php";

$schedule = new FullSchedule ($connection);
$schedule.render();

?>

<form action="Admin.php?view=actions" method="get" class="back-btn" style="text-align:left">
	<button type="submit">Back</button>
</form>

</div>
</div>
