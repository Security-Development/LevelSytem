<?php

namespace Neo\utils;

use Neo\ExtendsLib;
use Neo\Level;
use pocketmine\permission\DefaultPermissionNames;
use pocketmine\player\Player;
use pocketmine\Server;

class CommandUtils {

    public static function initCommand() {
        ExtendsLib::initCommand(
            "레벨", Level::getLang("command.level"),
            function(Player $player, array $args) {
                $player->sendMessage(Level::getLang("my.level.message", [LevelUtils::getLevel($player), LevelUtils::getXp($player), LevelUtils::getXpPercent($player)]));

            }, DefaultPermissionNames::BROADCAST_USER
        );

        ExtendsLib::initCommand(
            "레벨순위", Level::getLang("command.level.rank"),
            function(Player $player, array $args) {
                $key = 1;
                
                if( isset($args[0]) && is_numeric($args[0]) )
                    $key = $args[0];
                
                $key = ($key - 1) * 5;
                $db = [];
                $rank = 0;


                foreach( LevelUtils::getLevelRank() as $kPlayer => $level ) {
                    $handlePlayer = Server::getInstance()->getPlayerByPrefix($kPlayer);
                    array_push($db, [
                        "player" => $handlePlayer->getName(),
                        "level" => $level,
                        "rank" => ++$rank,
                        "xp" => LevelUtils::getXp($handlePlayer),
                        "xpPercent" => LevelUtils::getXpPercent($handlePlayer)
                    ]);
                }

                for( $i = $key - 1; $i < $key + 4; $i++ ) 
                    if( isset($db[$i]) ) {
                        $arr = $db[$i];

                        $player->sendMessage(Level::getLang("level.rank", [$arr["rank"],  $arr["player"], $arr["level"], $arr["xp"], $arr["xpPercent"]]));
                        continue;
                    }

                }, DefaultPermissionNames::BROADCAST_USER
            );

        ExtendsLib::initCommand(
            "레벨관리", Level::getLang("command.level.management"),
            function(Player $player, array $args) {

            }, DefaultPermissionNames::BROADCAST_USER
        );
    }
}
?>