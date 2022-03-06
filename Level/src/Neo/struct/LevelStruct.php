<?php

namespace Neo\struct;

use Closure;
use pocketmine\player\Player;

interface LevelStruct {

    # 처음 접속한 플레이어의 정보를 초기화하는 메소드
    public static function initLevel(Player $player) : void ;

    # 레벨의 최대치를 정수형으로 리턴되어 받아오는 메소드
    public static function getMaxLevel() : int;

    # 경험치의 최대치를 정수형으로 리턴되어 받아오는 메소드
    public static function getMaxXp(Player $player) : int;

    # 레벨을 정수형으로 리턴되어 받아오는 메소드
    public static function getLevel(Player $player) : int;

    # 경험치를 정수형으로 리턴되어 받아오는 메소드
    public static function getXp(Player $player) : int;

    # 레벨을 설정 하는 메소드
    public static function setLevel(Player $player, int $level) : void;

    # 경험치를 설정 하는 메소드
    public static function setXp(Player $player, int $exp) : void;

    # 레벨을 추가하는 메소드
    public static function incLevel(Player $player, int $level) : void;

    # 경험치를 추가하는 메소드
    public static function incXp(Player $player, int $exp) : void;

    # 경험치를 감소하는 메소드
    public static function decLevel(Player $player, int $level) : void;

    # 경험치를 감소하는 메소드
    public static function decXp(Player $player, int $level) : void;

    # 레벨을 올릴 때 호출 되는 메소드
    public static function LevelUp(Player $player, int $number) : void;

    # 최대 레벨을 달성 했을때를 호출 되는 메소드
    public static function AchivedMaxLevel(Closure $closure) : void;

    # 레벨의 순위를 가져오는 메소드
    public static function getLevelRank() : array;

    public static function getXpPercent(Player $player) : float;

}
?>