<?php

namespace Neo;

use Neo\event\LevelEvent as EvenLevelEvent;
use Neo\utils\CommandUtils;
use Neo\utils\TraitDataUtils;
use pocketmine\plugin\Plugin;
use pocketmine\plugin\PluginBase;
use pocketmine\Server;

class Level extends PluginBase {

    use TraitDataUtils;

    public static ?Plugin $instance = null;

    public function onEnable() : void{
        Server::getInstance()->getPluginManager()->registerEvents(new EvenLevelEvent(), $this);
        self::$instance = $this;
        $this->saveResource("data.json");
        $this->saveResource("msg.ini");
        CommandUtils::initCommand();
    }

    public static function getLang(string $lang, array $param = []) : string{ 
        $text = str_replace('\n', "\n", (parse_ini_file(self::getInstance()->getDataFolder()."\\msg.ini", true))[$lang]); # \n필터링 줘서 줄바꿈 정상 작동 

        foreach($param as $key => $value) {
            $text = str_replace("{%".$key."}", (string) $value, $text);
        }

        return $text;
    }

    public static function getInstance() {
        return self::$instance;
    }
}
?>