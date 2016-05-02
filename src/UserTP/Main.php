<?php

namespace UserTP;

use pocketmine\command\Command;
use pocketmine\command\CommandExecutor;
use pocketmine\command\CommandSender;
use pocketmine\Server;
use pocketmine\event\player\PlayerInteractEvent;
use pocketmine\event\Listener;
use pocketmine\math\Vector3;
use pocketmine\Player;
use pocketmine\IPlayer;
use pocketmine\plugin\PluginBase;
use pocketmine\utils\TextFormat;
use pocketmine\utils\Config;

    class Main extends PluginBase{

        public function onLoad(){
                $this->getLogger()->info("UserTP enabled.");
				$this->tp_reciver = [];
				$this->tp_sender = [];
				$this->tp_invite_reciver = [];
				$this->tp_invite_sender = [];
        }
		    public function onCommand(CommandSender $sender, Command $command, $label, array $args){
                if(strtolower($cmd->getName()) === "tpa"){
					if(!isset($args[0])) {
						return false;
					} else {
						switch($args[0]) {
							case "ask":
							if ($sender instanceof Player){
								 if (count($args) < 2) {
									 if (trim(strtolower($sender->getName())) === trim(strtolower($args[1]))) {
										 $sender->sendMessage(TextFormat::RED . "Why are you trying to teleport to yourself?");
										 return true;
									 }
									 if ($this->getServer()->getPlayer($args[1]) instanceof Player) {
										 $reciever = $this->getServer()->getPlayer($args[1]);
										 $this->tp_sender[$reciever->getName()]  = $sender->getName();
										 $this->tp_reciver[$sender->getName()] = $reciever->getName();
										 $this->getServer()->getPlayer($this->tp_reciver[$sender->getName()])->sendMessage(TextFormat::GOLD . $sender->getName() . TextFormat::WHITE . " request to teleport to you !\n Type /tpa accept $sender->getName() to teleport him to you or /tpa decline to decline the teleportation");
								    } else {
										$sender->sendMessage(TextFormat::RED . "Player $args[1] does NOT exist ! Don't try to teleport to a ghost !");
										return true;
									}
									} else {
										$sender->sendMessage(TextFormat::RED ."Who are you trying to teleport to?");
										return false;
									}

							 } else {
								 $sender->sendMessage(TextFormat::RED."Did you think that you can tp a RCON?");
								 return true;
							 }
							return true;
							break;
							
							case "invite":
							if ($sender instanceof Player){
								 if (count($args) < 2) {
									 if (trim(strtolower($sender->getName())) === trim(strtolower($this->getServer()->getPlayer($args[1])->getName()))) {
										 $sender->sendMessage(TextFormat::RED . "Why are you trying to teleport to yourself?");
										 return true;
									 }
									 if ($this->getServer()->getPlayer($args[1]) instanceof Player) {
										 $reciever = $this->getServer()->getPlayer($args[1]);
										 $this->tp_invite_sender[$reciever->getName()]  = $sender->getName();
										 $this->tp_invite_reciver[$sender->getName()] = $reciever->getName();
										 $this->getServer()->getPlayer($this->tp_invite_reciver[$sender->getName()])->sendMessage(TextFormat::GOLD . $sender->getName() . TextFormat::WHITE . " request you to teleport to him !\n Type /tpa accept $sender->getName() to teleport to him or /tpa decline to decline the teleportation");
								    } else {
										$sender->sendMessage(TextFormat::RED . "Player $args[1] does NOT exist ! Don't try to teleport to a ghost !");
										return true;
									}
									} else {
										$sender->sendMessage(TextFormat::RED . "Who are you trying to teleport to?");
										return false;
									}

							 } else {
								 $sender->sendMessage(TextFormat::RED."Did you think that you can tp to  a RCON?");
								 return true;
							 }
							return true;
							break;
							
							
							case "accept":
							if ($sender instanceof Player){
								 if (count($args) < 2) {
									 if (!isset($this->tp_sender[$sender->getName()]) or !isset($this->tp_invite_sender[$sender->getName()])) {
										 $sender->sendMessage("No one are trying to teleport to you / to him.");
										 return true;
									 }
									 
									 
									 if(isset($this->tp_sender[$sender->getName()])) { // if it's an ask
									 if ($this->getServer()->getPlayer($this->tp_sender[$sender->getName()]) instanceof Player) {
										 $tpsender = $this->getServer()->getPlayer($this->tp_sender[$sender->getName()]);
										 $tpsender->teleport(new Vector3($sender->x, $sender->y, $sender->z));
										 unset($this->tp_sender[$sender->getName]);
										 unset($this->tp_reciver[$tpsender->getName]);
										 $sender->sendMessage(TextFormat::GOLD . $tpsender->getName() . TextFormat::GREEN . " succefully teleported to you");
										 $tpsender->sendMessage(TextFormat::GREEN."You have been teleported to ".TextFormat::GOLD . $sender->getName());
									 }
									 
									 
									 } elseif(isset($this->tp_invite_sender[$sender->getName()])) { // if it's an invite
									 if ($this->getServer()->getPlayer($this->tp_invite_sender[$sender->getName()]) instanceof Player) {
										 $tpsender = $this->getServer()->getPlayer($this->tp_invite_sender[$sender->getName()]);
										 $sender->teleport(new Vector3($tpsender->x, $tpsender->y, $tpsender->z));
										 unset($this->tp_invite_sender[$sender->getName]);
										 unset($this->tp_invite_reciver[$tpsender->getName]);
										 $tpsender->sendMessage(TextFormat::GOLD . $sender->getName() . TextFormat::GREEN . " teleported to you");
										 $sender->sendMessage(TextFormat::GREEN."You succefully teleported to ".TextFormat::GOLD . $tpsender->getName());
									 }
									 }
									} else {
										$sender->sendMessage(TextFormat::RED . "Who are you trying to teleport to?");
										return false;
									}

							 } else {
								 $sender->sendMessage(TextFormat::RED."Did you think that you can tp to  a RCON?");
								 return true;
							 }
							return true;
							break;
							
							case "decline":
							if ($sender instanceof Player){
								 if (count($args) < 2) {
									 if (!isset($this->tp_sender[$sender->getName()]) or !isset($this->tp_invite_sender[$sender->getName()])) {
										 $sender->sendMessage("No one are trying to teleport to you / to him.");
										 return true;
									 }
									 
									 
									 if(isset($this->tp_sender[$sender->getName()])) { // if it's an ask
									 if ($this->getServer()->getPlayer($this->tp_sender[$sender->getName()]) instanceof Player) {
										 $tpsender = $this->getServer()->getPlayer($this->tp_sender[$sender->getName()]);
										 unset($this->tp_sender[$sender->getName]);
										 unset($this->tp_reciver[$tpsender->getName]);
										 $sender->sendMessage(TextFormat::GREEN."You have declined teleport request of ".TextFormat::GOLD . $sender->getName());
										 $tpsender->sendMessage(TextFormat::GOLD . $sender->getName() . TextFormat::GREEN . " declined your teleport request");
									 }
									 
									 
									 } elseif(isset($this->tp_invite_sender[$sender->getName()])) { // if it's an invite
									 if ($this->getServer()->getPlayer($this->tp_invite_sender[$sender->getName()]) instanceof Player) {
										 $tpsender = $this->getServer()->getPlayer($this->tp_invite_sender[$sender->getName()]);
										 unset($this->tp_invite_sender[$sender->getName]);
										 unset($this->tp_invite_reciver[$tpsender->getName]);
										 $sender->sendMessage(TextFormat::GREEN."You have declined teleport request of ".TextFormat::GOLD . $sender->getName());
										 $tpsender->sendMessage(TextFormat::GOLD . $sender->getName() . TextFormat::GREEN . " declined your teleport request");
									 }
									 }
									} else {
										$sender->sendMessage(TextFormat::RED . "Who are you trying to teleport to?");
										return false;
									}

							 } else {
								 $sender->sendMessage(TextFormat::RED."Did you think that you can tp to  a RCON?");
								 return true;
							 }
							return true;
							break;
						}
					}
				}
			}
	}