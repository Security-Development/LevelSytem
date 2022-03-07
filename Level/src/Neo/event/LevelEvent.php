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
        LevelUtils::incXp($event->getPlayer(), 1250);
        $event->getPlayer()->sendMessage('xp 1250획득');
    }

    public function onLevelUp(LevelChangeEvent $event) {
        $player = $event->getPlayer();
        if( $event->getAction() == LevelChangeEvent::UP ) {
            $player->sendMessage("레벨업을 하셨군요~");
        }
    }

    public function onXpChange(XpChangeEvent $event) : void {
        $player = $event->getPlayer();
        $data = Level::getData();
    
        if( LevelUtils::getMaxXp($player) <= $event->getNewXp() ) {
            $level = round($event->getNewXp() / LevelUtils::getMaxXp($player));
            LevelUtils::LevelUp($player, $level );
            LevelUtils::setXp($player, $event->getNewXp() - (LevelUtils::getMaxXp($player) * $level ) );
            LevelUtils::setMaxXp($player, ceil(5000 * LevelUtils::getLevel($player) * 2.7));
        }
        $player->getXpManager()->setXpLevel($data[$player->getName()]['level']);
        $player->getXpManager()->setXpProgress(LevelUtils::getXpPercent($player) / 100);

    }
}
?>
