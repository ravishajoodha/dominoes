<?php
#-------------------------------
#	ravishajoodha@gmail.com
#	2020-04-26
#-------------------------------
require_once __DIR__ . '/includes/includes.php';
require_once __DIR__ . '/includes/header.php';


// Start New Game 
/*
All data will be sorted in the session variable
$_SESSION['stock']
$_SESSION['player1']
$_SESSION['player2']
$_SESSION['playing_board']
$_SESSION['top_bottom']
*/
//clear session variable 
//unset($_SESSION);
session_unset();
session_start();

//1. The 28 tiles are shuffled face down and form the stock. 
$counter = 0;
for($x=0; $x<=6; $x++){
	
	for($y=$x; $y<=6; $y++){
		//echo $x.":".$y;		
		$_SESSION['stock'][$counter]['L'] = $x;
		$_SESSION['stock'][$counter]['R'] = $y;
		
		$counter++;
	}
}


//Shuffle the stock
shuffle($_SESSION['stock']);


//Pull 1 tile for the playing board
$_SESSION['playing_board'][] = $_SESSION['stock'][0];
$_SESSION['top_bottom']['T'] = $_SESSION['stock'][0]['L'];
$_SESSION['top_bottom']['B'] = $_SESSION['stock'][0]['R'];
unset($_SESSION['stock'][0]);



//Each player draws seven tiles. 

//Give player 1 seven random tiles 
$counter = 0;
foreach($_SESSION['stock'] AS $s_key => $s_data){
	$counter++;	
	if($counter==8){
		break;
	}
	
	$_SESSION['player1'][] = $s_data;
	unset($_SESSION['stock'][$s_key]);
}



//Give player 2 seven random tiles 
$counter = 0;
foreach($_SESSION['stock'] AS $s_key => $s_data){
	$counter++;
	if($counter==8){
		break;
	}
	
	$_SESSION['player2'][] = $s_data;
	unset($_SESSION['stock'][$s_key]);
}

//Create Imput boxes to show the status of the game. 
//this is to show that it's working :)
?>
<body class="body_style2">
	<div>
		<table style="width: 100%;">
			<tr>
				<td style="padding: 7px;">
					<div id="message_div" class="message_div">Welcome to Dominoes - your move.</div>
				</td>
			</tr>
		</table>
	</div>
	
	<div id="scores_div" class="scores_div">
		<div class="score_div">
			Player 1<br/>
			<input type="text" id="player1_board_tile_count" value="<?=count($_SESSION['player1'])?>" readonly/>
		</div>
		
		<div class="score_div">
			Stock Tiles<br/>
			<input type="text" id="txt_stock" value="<?=count($_SESSION['stock'])?>" readonly/>
		</div>
		
		<div class="score_div">
			Player 2<br/>
			<input type="text" id="player2_board_tile_count" value="<?=count($_SESSION['player2'])?>" readonly/>
		</div>
	</div>
	
	<div style="clear: both;"></div>
	<hr>
	
	<div>
		<table style="width: 100%;" class="play_area_table">
			<tr style="">
				<th style="width: 180px;">YOU</th>
				<th>PLAY AREA</th>
				<th style="width: 180px;">COMPUTER</th>
			</tr>
			
			<tr>
				<td>
					<button class="action_btn btn-blue btn-small" id="pick_from_stock" onclick="pull_tile_from_stock();">
						<i class="fas fa-truck-pickup"></i> Pull from stock
					</button>
					<hr/>
					
					<div id="load_player1_board">...</div>
				</td>
				<td>
					<div class="score_div" style="width: 100%;">
						You can play  
						<input type="text" id="txt_top" value="<?=$_SESSION['top_bottom']['T']?>"> OR 
						<input type="text" id="txt_btm" value="<?=$_SESSION['top_bottom']['B']?>">
					</div>
					<hr/>
					<div id="load_playing_area_board">...</div>
				</td>
				<td>
					<br><br>
					<div id="load_player2_board">...</div>
				</td>
			</tr>
		</table>
	</div>
</body>



<script>
var allow_play = true;
	
	function load_play_area(){
		var method = "load_playing_area_board";
		
		$('#load_playing_area_board').load('./game_play_post.php', {
			method:method
		}, 
		function(result){
			//load message
			//console.log(result);
		});
	}
	
	function load_player1_tiles(){
		var method = "load_player1_board";
		
		$('#load_player1_board').load('./game_play_post.php', {
			method:method
		}, 
		function(result){
			//load message
			//console.log(result);
		});
	}
	
	function load_player2_tiles(){
		var method = "load_player2_board";
		
		$('#load_player2_board').load('./game_play_post.php', {
			method:method
		}, 
		function(result){
			//load message
			//console.log(result);
		});		
	}
	
	
	function update_score(){
		var method = "update_score";
		
		$.post('./game_play_post.php', {
			method:method
		}, 
		function(result){
			//load message
			//console.log(result);
			var data = jQuery.parseJSON(result);					
			
			$('#txt_stock').val(data.txt_stock);	
			$('#player1_board_tile_count').val(data.player1_board_tile_count);	
			$('#player2_board_tile_count').val(data.player2_board_tile_count);			
			$('#txt_top').val(data.txt_top);			
			$('#txt_btm').val(data.txt_btm);	

			//check scores
			if(data.player1_board_tile_count==0){
				you_win();
			}
			if(data.player2_board_tile_count==0){
				computer_wins();
			}
		});
	}
	
	
	function pull_tile_from_stock(){
		var method = "pull_tile_from_stock";
		
		$.post('./game_play_post.php', {
			method:method
		}, 
		function(result){
			//console.log(result);
			var data = jQuery.parseJSON(result);					
			//console.log(data);
			
			if(data.status=="pass"){
				$('#message_div').html(data.msg);
				update_score();
				load_play_area();
				load_player1_tiles();
				disable_all_buttons();
				computers_turn();
			}
			else if(data.status=="nothing_to_draw"){
				$('#message_div').html(data.msg);
			}
			else{
				//you loose
				computer_wins();
			}
		});
	}
	
	function play_tile(tile_id){
		if(allow_play){
			var method = "play_tile";
			$('#message_div').html('Checking Play...');
			disable_all_buttons();
			
			//Check if user can play that tile
			$.post('./game_play_post.php', {
				method:method,
				tile_id:tile_id
			}, 
			function(result){
				//console.log(result);
				var data = jQuery.parseJSON(result);					
				//console.log(data);
				if(data.status == "pass"){
					$('#message_div').html(data.msg);
					update_score();
					$('#'+tile_id).hide();
					computers_turn();
					load_play_area();
				}
				else{
					$('#message_div').html(data.msg);
					enable_all_buttons();
				}				
			});
			
		}
	}
	
	function disable_all_buttons(){
		$('#pick_from_stock').prop('disabled', true);
		allow_play = false;
	}
	function enable_all_buttons(){
		$('#pick_from_stock').prop('disabled', false);
		allow_play = true;
	}
	
	function computers_turn(){
		var method = "computers_turn";
		
		$.post('./game_play_post.php', {
			method:method
		}, 
		function(result){
			//console.log(result);
			var data = jQuery.parseJSON(result);					
			//console.log(data);
			
			if(data.status=="pass"){
				$('#message_div').html(data.msg);
				update_score();
				load_play_area();
				enable_all_buttons();
				load_player2_tiles();
			}
			else{
				you_win();
			}			
		});
	}
	
	$(document).ready(function(){
		load_play_area();
		load_player1_tiles();
		load_player2_tiles();
		//update_score();
	});
	
	function you_win(){
		$('#message_div').html("YOU WON!!! YAAAY! :) ");
		$("#message_div").css("background-color","#FFD700");
		disable_all_buttons();
	}
	function computer_wins(){
		$('#message_div').html("COMPUTER WINS!!!");
		$("#message_div").css("background-color","#8B4513");
		disable_all_buttons();
	}
	
</script>