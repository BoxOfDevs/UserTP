<?php

namespace TestMe;

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
        }
		    public function onCommand(CommandSender $sender, Command $command, $label, array $args){
		//utp ask <player>
                if($cmd->getName() == "test"){
                $sender->sendMessage (TextFormat::GOLD . ("Usage: /utp ask <player>"));if ($sender instanceof Player)
                {
                  if ((count($args) != 0) && (count($args) < 2)) {
                            if (trim(strtolower($sender->getName())) == trim(strtolower($args[0]))) {
                                $sender->sendMessage(TextFormat::RED . $this->config->get("utp_ask_message"));
                                return true;
                            }
                            $this->tp_sender  = $sender->getName();
                            $this->tp_reciver = $args[0];
                            if ($this->getServer()->getPlayer($this->tp_reciver) instanceof Player) {
                                $this->getServer()->getPlayer($this->tp_reciver)->sendMessage(TextFormat::GOLD . $this->tp_sender . TextFormat::WHITE . ' '.$this->config->get("utp_ask_request_message"));
						} else {
                                $sender->sendMessage(TextFormat::RED . $this->config->get("utp_ask_player_offline"));
                                return true;
                            }
                        } else {
                            $sender->sendMessage(TextFormat::RED . $this->config->get("utp_ask_player_invalid_usgae"));
                            return false;
                        }

                }
                else
                {
                    $sender->sendMessage(TextFormat::RED.$this->config->get("utp_ask_player_console_usage"));
                    return true;
                }