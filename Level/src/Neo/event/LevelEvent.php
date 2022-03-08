<?php

namespace  Neo\event;

use Neo\Level;
use Neo\utils\LevelUtils;
use pocketmine\event\Listener;
use pocketmine\event\player\PlayerJoinEvent;
use pocketmine\event\player\PlayerJumpEvent;

class LevelEvent implements Listener {
    
    public function onJoin(PlayerJoinEvent $event) : void {
        LevelUtils::initLevel($event->getPlayer());
    }

    public function onJump(PlayerJumpEvent $event) : void {
        LevelUtils::incXp($event->getPlayer(), 60200);
        $event->getPlayer()->sendMessage('xp 60200획득');
    }

    public function onLevelUp(LevelChangeEvent $event) {
        $player = $event->getPlayer();
        if( $event->getAction() == LevelChangeEvent::UP ) {
            $player->sendMessage("레벨업을 하셨군요~");
        }
    }

    public function onXpChange(XpChangeEvent $event) : void {

        $player = $event->getPlayer();
        $exp = $event->getNewXp();
        do{
            $exp -= (5000 * LevelUtils::getLevel($player) * 2.7);

            if( $event->getNewXp() > LevelUtils::getMaxXp($player)){
                LevelUtils::LevelUp($player, 1);
                LevelUtils::setMaxXp($player, (5000 * LevelUtils::getLevel($player) * 2.7));
            }

        } while($exp > LevelUtils::getMaxXp($player));

        if( $exp > 0) {
            LevelUtils::setXp($player, $exp, false);
            $player->getXpManager()->setXpLevel(LevelUtils::getLevel($player));
        }

        $player->getXpManager()->setXpProgress(LevelUtils::getXpPercent($player) / 100);
        


    }
}
?>
