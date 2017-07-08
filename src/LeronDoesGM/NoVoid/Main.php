<?php

namespace LeronDoesGM\NoVoid;

    use pocketmine\plugin\PluginBase;
    use pocketmine\command\Command;
    use pocketmine\command\CommandExecutor;
    use pocketmine\command\CommandSender;
    use pocketmine\command\ConsoleCommandSender;
    use pocketmine\command\ConsoleCommandExecutor;
    use pocketmine\event\Listener;
    use pocketmine\level\Position;
    use pocketmine\level\Level;
    use pocketmine\Player;
    use pocketmine\entity\Entity;
    use pocketmine\math\Vector3;
    use pocketmine\utils\Config;
    use pocketmine\event\player\PlayerMoveEvent;

class Main extends PluginBase implements Listener{
private $api, $server, $path;
    
public function onEnable(){
    $this->getServer()->getPluginManager()->registerEvents($this, $this);
    $this->saveDefaultConfig();
    $this->getResource("config.yml");
    $this->getLogger()->info("NoVoid Loaded!");

}

    public function onCommand(CommandSender $sender, Command $cmd, $label, array $args){
        switch($cmd->getName()){
            case "nv":
                    if(!$sender Instanceof Player){
                        $sender->sendMessage("[NoVoid] You can only use NoVoid in game!");
                        return true;
                    }else{
                        $X = $sender->getFloorX();
                        $Y = $sender->getFloorY();
                        $Z = $sender->getFloorZ();
                        $Level = $sender->getLevel()->getName();
                        $this->getConfig()->set("X", $X);
                        $this->getConfig()->set("Y", $Y);
                        $this->getConfig()->set("Z", $Z);
                        $this->getConfig()->set("Level", $Level);
                        $this->getConfig()->set("enableConf", true);
                        $this->getConfig()->save();
                        $sender->sendMessage("[NoVoid] New location!");
                        return true;
                    }
                return true;
                
            case "heart":
                if($sender->hasPermission("novoid.command.nv")){
                    if(isset($args[0])){
                        if(is_numeric($args[0])){
                            $this->getConfig()->set("hearts", $args[0]);
                            $this->getConfig()->save();
                            $sender->sendMessage("§bAmount hearts you lose: " . $args[0]);
                            return true;
                        }else{
                            $sender->sendMessage("§4Your Amount is not correct");
                            return true;
                        }
                    }else{
                        $sender->sendMessage("§4Use /heart <Amount>");
                        return true;
                    }
                }else{
                    $sender->sendMessage("§4You don't have the permission for this command.");
                    return true;
                }
                return true;

            default:
                return false;
        }
    }

    
    
    public function onVoidLoop(PlayerMoveEvent $event){
        if($event->getTo()->getFloorY() < 2){
            $enableConf = $this->getConfig()->get("enableConf");
            $X = $this->getConfig()->get("X");
            $Y = $this->getConfig()->get("Y");
            $Z = $this->getConfig()->get("Z");
            $Level = $this->getConfig()->get("Level");
            $player = $event->getPlayer();
            if($enableConf === false){
                $player->teleport($this->getServer()->getDefaultLevel()->getSpawn());
                $player->setHealth($player->getHealth() - ($this->getConfig()->get("hearts")));
            }else{
                $player->teleport(new Vector3($X, $Y+4, $Z, $Level));
                $player->setHealth($player->getHealth() - ($this->getConfig()->get("hearts")));
            }
        }

                  }
    
    public function onDisable(){
        $this->getConfig()->save();
        $this->getLogger()->info("NoVoid Unloaded! By www.allo-serv.fr");
    }
    
}

    