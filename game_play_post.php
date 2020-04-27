<?php
#-------------------------------
#	ravishajoodha@gmail.com
#	2020-04-26
#-------------------------------
session_start();
require_once __DIR__ . '/includes/functions.php';

//variables
$method = $_REQUEST['method'];
//$_SESSION['stock']
//$_SESSION['player1']
//$_SESSION['player2']
//$_SESSION['playing_board']

if(isset($method)){
	
	//Draw the center playing board 
	if($method == "load_playing_area_board"){		
		
		echo "<center>";
		foreach($_SESSION['playing_board'] AS $pb_key => $pb_data){
			?>
			<div class="p1tiles">
				<div class="play_area_tile">
					<?=$pb_data['L']." | ".$pb_data['R'];?>
				</div>
				<br>
			</div>
			<?
		}		
		echo "</center>";
	}
	
	//Draw palayer1's board
	if($method == "load_player1_board"){
		
		echo "<center>";
		foreach($_SESSION['player1'] AS $ply_key => $ply_data){
			$id = $ply_data['L']."_".$ply_data['R'];
			?>
			<div id="<?=$id?>" onclick="play_tile('<?=$id?>')" class="p1tiles">
				<div class="play_area_tile_p1">
					<?=$ply_data['L']." | ".$ply_data['R'];?>
				</div>
				<br>
			</div>
			<?
		}
		
		echo "</center>";
	}
	
	//Draw palayer2's board
	if($method == "load_player2_board"){	
		echo "<center>";
		foreach($_SESSION['player2'] AS $ply_key => $ply_data){
			?>
			<div id="<?=$ply_data['L']."_".$ply_data['R'];?>">
				<div class="play_area_tile">
					<?=$ply_data['L']." | ".$ply_data['R'];?>
				</div>
				<br>
			</div> 
			<?
		}
		echo "</center>";
	}
	
	
	//update scores
	if($method == "update_score"){
		$json_data = array(
		"txt_stock"   => count($_SESSION['stock']), 
		"player1_board_tile_count"   => count($_SESSION['player1']), 
		"player2_board_tile_count"  => count($_SESSION['player2']),
		"txt_top"  => $_SESSION['top_bottom']['T'],
		"txt_btm"  => $_SESSION['top_bottom']['B']
		);
		echo json_encode($json_data);			
		exit;
	}
	
	
	//Player one plays a tile
	if($method == "play_tile"){	
		//Variables
		$tile_id = $_REQUEST['tile_id'];	
		$cur_top = $_SESSION['top_bottom']['T'];
		$cur_btm = $_SESSION['top_bottom']['B'];
		$rtn_tile = array();
			
		if($tile_id){
			$tile = explode("_", $tile_id);
			
			//check for match 
			$pass = false;
			//Check Left number against the top
			if($tile[0] == $cur_top){
				$rtn_tile['L'] = $tile[0];
				$rtn_tile['R'] = $tile[1];
				$position = "top";
				$_SESSION['top_bottom']['T'] = $tile[1];
				$pass = true;
			}
			//Check Right number against the top
			elseif($tile[1] == $cur_top){
				$rtn_tile['L'] = $tile[1];
				$rtn_tile['R'] = $tile[0];
				$position = "top";
				$_SESSION['top_bottom']['T'] = $tile[0];
				$pass = true;
			}
			//Check Left number against the bottom
			elseif($tile[0] == $cur_btm){
				$rtn_tile['L'] = $tile[1];
				$rtn_tile['R'] = $tile[0];
				$position = "btm";
				$_SESSION['top_bottom']['B'] = $tile[1];
				$pass = true;
			}
			//Check Right number against the bottom
			elseif($tile[1] == $cur_btm){
				$rtn_tile['L'] = $tile[0];
				$rtn_tile['R'] = $tile[1];
				$position = "btm";
				$_SESSION['top_bottom']['B'] = $tile[0];
				$pass = true;
			}
			
			if($pass){
				//remove from player 1 array
				$_SESSION['player1'] = _remove_arr_elm($_SESSION['player1'], $tile[0], $tile[1]);
				
				//Add to play area array
				if($position=="top"){
					array_unshift($_SESSION['playing_board'], $rtn_tile);  //add to start of array
				}
				else{
					$_SESSION['playing_board'][] = $rtn_tile; //add to end of array
				}
				
				$msg =  "Play complete, computers turn...";
				
				$json_data = array(
					"status"   => 'pass', 
					"msg"   => $msg
				);
				echo json_encode($json_data);			
				exit;
				
			}
			else{
				$msg = "Incorrect play, please try again.";
			}
		}
		else{
			$msg = "Unable to process tile, please try again.";
		}
		
		$json_data = array(
			"status"   => 'fail', 
			"msg"   => $msg
		);
		echo json_encode($json_data);			
		exit;
		
	}
	
		
	//Computer plays
	if($method == "computers_turn"){
		//Variables
		$cur_top = $_SESSION['top_bottom']['T'];
		$cur_btm = $_SESSION['top_bottom']['B'];
		$rtn_tile = array();
		
		//if pass is false, the computer loses 
		$pass = false;
		
		//loop though computers tiles
		foreach($_SESSION['player2'] AS $p2_key => $tile){			
			//Check Left number against the top
			if($tile['L'] == $cur_top){
				$rtn_tile['L'] = $tile['L'];
				$rtn_tile['R'] = $tile['R'];
				$position = "top";
				$_SESSION['top_bottom']['T'] = $tile['R'];
				$pass = true;
			}
			//Check Right number against the top
			elseif($tile['R'] == $cur_top){
				$rtn_tile['L'] = $tile['R'];
				$rtn_tile['R'] = $tile['L'];
				$position = "top";
				$_SESSION['top_bottom']['T'] = $tile['L'];
				$pass = true;
			}
			//Check Left number against the bottom
			elseif($tile['L'] == $cur_btm){
				$rtn_tile['L'] = $tile['R'];
				$rtn_tile['R'] = $tile['L'];
				$position = "btm";
				$_SESSION['top_bottom']['B'] = $tile['R'];
				$pass = true;
			}
			//Check Right number against the bottom
			elseif($tile['R'] == $cur_btm){
				$rtn_tile['L'] = $tile['L'];
				$rtn_tile['R'] = $tile['R'];
				$position = "btm";
				$_SESSION['top_bottom']['B'] = $tile['L'];
				$pass = true;
			}
			
			if($pass){
				//remove from player 1 array
				$_SESSION['player2'] = _remove_arr_elm($_SESSION['player2'], $tile['L'], $tile['R']);
				
				//Add to play area array
				if($position=="top"){
					array_unshift($_SESSION['playing_board'], $rtn_tile);  //add to start of array
				}
				else{
					$_SESSION['playing_board'][] = $rtn_tile; //add to end of array
				}
				
				$status = "pass";
				$msg =  "Your Turn.";
				
				break; //exit loop
			}
		}
		
		if(!$pass){
			//Draw a tile
			if(count($_SESSION['stock'])>0){
				$status = "pass";
				//take one from stack and add to Player 2 arr
				
				foreach($_SESSION['stock'] AS $s_key => $s_data){
					$_SESSION['player2'][] = $s_data;
					unset($_SESSION['stock'][$s_key]);
					
					break;
				}
			}
			//If there is nothing to draw then Plyer 1 wins 
			else{
				//Player 1 WINS!!!!! :) YAAAY!!!!
			}
		}
		
		$json_data = array(
			"status"   	=> $status,
			"msg"   	=> $msg
		);
		echo json_encode($json_data);
		exit;
	}
	
	
	//Player one plays a tile
	if($method == "pull_tile_from_stock"){
		$pass=false;
		//check if there is anything to pull
		if(count($_SESSION['stock'])>0){
			foreach($_SESSION['stock'] AS $s_key => $s_data){
				$_SESSION['player1'][] = $s_data;
				unset($_SESSION['stock'][$s_key]);
				
				break;
			}			
			
			$status = "pass";
			$msg = "Computers turn...";
		}	
		else{
			//check if player 1 has anything to play
			//Variables
			$cur_top = $_SESSION['top_bottom']['T'];
			$cur_btm = $_SESSION['top_bottom']['B'];
			
			//loop though computers tiles
			foreach($_SESSION['player1'] AS $p1_key => $tile){				
				//Check Left number against the top
				if($tile['L'] == $cur_top OR $tile['R'] == $cur_top OR $tile['L'] == $cur_btm OR $tile['R'] == $cur_btm){
					$pass = true;
					break;
				}
			}
			if($pass){
				$status = "nothing_to_draw";
				$msg = "There is nothing in the stack.";
			}
			else{
				//ELSE Player 2 WINS <3
				$status = "fail";
				$msg = "COMPUTER WINS!!!";	
			}
		}
		
		$json_data = array(
			"status"   	=> $status,
			"msg"   	=> $msg
		);
		echo json_encode($json_data);			
		exit;
	}
}
?>