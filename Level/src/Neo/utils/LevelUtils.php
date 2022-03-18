<?php

namespace Neo\utils;

use Closure;
use Neo\event\LevelChangeEvent;
use Neo\event\XpChangeEvent;
use Neo\struct\LevelStruct;
use Neo\Level;
use pocketmine\lang\Language;
use pocketmine\network\mcpe\protocol\PlaySoundPacket;
use pocketmine\player\Player;
use pocketmine\Server;

class LevelUtils implements LevelStruct {

    public static function initLevel(Player $player) : void
    {
        $data = Level::getData();
        if( !isset($data[$player->getName()]) ) {
            $data[$player->getName()] = [
                'level' => 1,
                'xp' => 0,
                'maxXp' => 5000 * 2.7
            ];
            Level::setData($data);

            $player->getXpManager()->setXpLevel($data[$player->getName()]['level']);
            $player->getXpManager()->setXpProgress(self::getXpPercent($player) / 100);

        }
    }

    public static function getMaxLevel(): int
    {
        return (Level::getData())['maxLevel'];
    }

    public static function getMaxXp(Player $player): int
    {
        return (Level::getData())[$player->getName()]['maxXp'];
    }

    public static function getLevel(Player $player) : int {        
        return (Level::getData())[$player->getName()]['level'];
    }

    public static function getXp(Player $player) : int
    {
        return (Level::getData())[$player->getName()]['xp'];
    }

    public static function setLevel(Player $player, int $level, bool $cancelled=true): void
    {
        if( $cancelled )
            (new LevelChangeEvent($player, self::getLevel($player), $level, LevelChangeEvent::DEAFULAT))->call();

        $data = Level::getData();
        $data[$player->getName()]['level'] = $level;
        Level::setData($data);
    }

    public static function setXp(Player $player, int $exp, bool $cancelled=true): void
    {
        if( $cancelled  )
            (new XpChangeEvent($player, self::getXp($player), $exp, XpChangeEvent::DEAFULAT))->call();
        $data = Level::getData();
        $data[$player->getName()]['xp'] = $exp;
        Level::setData($data);

    }

    public static function setMaxXp(Player $player, int $exp) : void {
        $data = Level::getData();
        $data[$player->getName()]['maxXp'] = $exp;
        Level::setData($data);
    }

    public static function incLevel(Player $player, int $level, array $cancelled= [true, true]): void
    {
        if( $cancelled[0] ) 
            (new LevelChangeEvent($player, self::getLevel($player), self::getLevel($player) + $level, LevelChangeEvent::UP))->call();

        self::setLevel($player, self::getLevel($player) + $level, $cancelled[1]);
    }

    public static function incXp(Player $player, int $exp, array $cancelled= [true, true]): void
    {
        if( $cancelled[0] )
            (new XpChangeEvent($player, self::getXp($player), self::getXp($player) + $exp, XpChangeEvent::UP))->call();
        self::setXp($player, self::getXp($player) + $exp, $cancelled[1]);
    }

    public static function decLevel(Player $player, int $level, array $cancelled= [true, true]): void
    {
        if( $cancelled[0] )
            (new LevelChangeEvent($player, self::getLevel($player), self::getLevel($player) - $level, LevelChangeEvent::DOWN))->call();

        self::setLevel($player, self::getLevel($player) - $level, $cancelled[1]);
    }

    public static function decXp(Player $player, int $exp, array $cancelled= [true, true]): void
    {
        if( $cancelled[0] )
            (new XpChangeEvent($player, self::getXp($player), self::getXp($player) - $exp, XpChangeEvent::DOWN))->call();
        self::setXp($player, self::getXp($player) - $exp, $cancelled[1]);
    }

    public static function LevelUp(Player $player, int $number): void
    {
        $player->sendTitle(Level::getLang("level.up", [self::getLevel($player) + $number]));

        $player->getNetworkSession()->sendDataPacket((new PlaySoundPacket)::create(
            "random.levelup", 
            $player->getLocation()->x, $player->getLocation()->y + 1, $player->getLocation()->z,
            0.5,
            $player->getLocation()->pitch
            )
        );

        self::incLevel($player, $number);
        
    }

    public static function RefreshUp(Player $player, int $newXp) : void{

        $exp = $newXp;
        do{
            $exp -= (5000 * self::getLevel($player) * 2.7);

            if( $newXp >= self::getMaxXp($player)){
                self::LevelUp($player, 1);
            }

            self::setMaxXp($player, (5000 * self::getLevel($player) * 2.7));

        } while($exp >= self::getMaxXp($player));

        if( $exp > 0) {
            self::setXp($player, $exp, false);
            $player->getXpManager()->setXpLevel(self::getLevel($player));
        }

        $player->getXpManager()->setXpProgress(self::getXpPercent($player) / 100);
    }

    public static function RefreshXp(Player $player) : void {
        self::setMaxXp($player, (5000 * self::getLevel($player) * 2.7));
    }

    public static function sendMessage(Player $player, string $msg) : void {
        $player->sendMessage(Level::getLang('msg.prefix').' '.$msg);
    }

    public static function AchivedMaxLevel(Closure $closure): void
    {
        
    }

    public static function getLevelRank() : array
    {
        $data = [];

        foreach( ($db = Level::getData()) as $key => $value ) {
            if( Server::getInstance()->getPlayerExact($key) == null)
                continue;
            
            $data[$key] = $db[$key]['level'];
        }
        arsort($data);
        return $data;
    }

    public static function getXpPercent(Player $player): float
    {
        return round((LevelUtils::getXp($player) / LevelUtils::getMaxXp($player)) * 100, 2);
    }

}
?>