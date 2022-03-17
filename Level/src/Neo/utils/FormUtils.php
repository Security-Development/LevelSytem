<?php

namespace Neo\utils;

use jojoe77777\FormAPI\CustomForm;
use jojoe77777\FormAPI\SimpleForm;
use Neo\ExtendsLib;
use pocketmine\player\Player;
use pocketmine\Server;

class FormUtils {

    private static array $typeText = [
        0 => "레벨",
        1 => "경험치"
    ];

    public static function LevelManagement(Player $player) : void{
        $form = new SimpleForm(function(Player $player, ?int $data) {
            if( is_null($data) )
                return;
            
            $data == 0 ? self::SelectForm($player, 0) : self::SelectForm($player, 1);
        });

        $form->setTitle("레벨 시스템 관리");
        $form->setContent("진행할 프로세스 버튼을 선택해 주세요.");
        $form->addButton("레벨");
        $form->addButton("경험치");

        $form->sendToPlayer($player);
    }

    public static function SelectForm(Player $player, int $type) : void {
        $PlayerData = [];

        foreach(Server::getInstance()->getOnlinePlayers() as $players) {
            $PlayerData[] = $players->getName();
        }

        $form = new CustomForm(function(Player $player, ?array $data) use($PlayerData, $type) {
            if( is_null($data) )
                return;
            
                $handlePlayer = Server::getInstance()->getPlayerByPrefix($PlayerData[$data[1]]);

            if( !is_null($data[2]) )
                foreach($PlayerData as $names) 
                    $handlePlayer = ExtendsLib::getPlayerHandle($names, $data[2]);
            
            if( !is_null($handlePlayer) )
                self::SubLevelForm($player, $type, $handlePlayer);
        });
        
        $typeText = self::$typeText[$type];

        $form->setTitle($typeText." 관리");
        $form->addLabel($typeText."을(를) 관리합니다.");
        $form->addDropdown("관리할 대상이 접속 중이라면 아래에서 선택해 주세요.", $PlayerData, 0);
        $form->addInput("기입란에 입력하여 지정하셔도 됩니다.");

        $form->sendToPlayer($player);

    }

    public static function SubLevelForm(Player $player, int $type, Player $target) : void {
        $form = new SimpleForm(function(Player $player, ?int $data) use($type, $target) {
            if( is_null($data) )
                return;

            match($data) {
                0 => self::SubIncForm($player, $type, $target),
                1 => self::SubDecForm($player, $type, $target),
                2 => self::SubSetForm($player, $type, $target),
                default => null
            };
        });

        $form->setTitle("프로세스 목록");
        $form->setContent($target->getName()."님에 대해 진행할 프로세스를 채팅에 입력해 주세요.");
        $form->addButton("증가");
        $form->addButton("감소");
        $form->addButton("설정");

        $form->sendToPlayer($player);

    }

    private static function getLabelText(Player $player, int $type) : string {
        return $player->getName()."님의 현재 ".self::$typeText[$type]."은(는) ". ($type ? LevelUtils::getXp($player) : LevelUtils::getLevel($player))." ". ($type ? 'XP' : 'LV')." 입니다.";
    }

    public static function SubIncForm(Player $player, int $type, Player $target) : void {
        $form = new CustomForm(function(Player $player , ?array $data) use($type, $target) {
            if( is_null($data) )
                return;
            
            if( !is_numeric($data[1]) || $data[1] < 0 ) {
                $player->sendMessage("입력하신 값 ".$data[1]."이 올바르지 않아 작업을 이행할 수 없습니다.");
                return;
            }
            
            if( $type )
                LevelUtils::incXp($target, $data[1], [true, false]);
            else 
                LevelUtils::incLevel($target, $data[1], [true, false]);

            $player->sendMessage($target->getName()."님의 ". self::$typeText[$type]."을(를) ".$data[1]."만큼 증가 시켰습니다.");
        });

        $form->setTitle("증가");
        $form->addLabel(self::getLabelText($target, $type));
        $form->addInput("증가량을 입력란에 입력해 주세요");

        $form->sendToPlayer($player);

    }

    public static function SubDecForm(Player $player, int $type, Player $target) : void {
        $form = new CustomForm(function(Player $player , ?array $data) use($type, $target) {
            if( is_null($data) )
                return;
            
            if( !is_numeric($data[1]) || $data[1] < 0 ) {
                $player->sendMessage("입력하신 값 ".$data[1]."이 올바르지 않아 작업을 이행할 수 없습니다.");
                return;
            }

            if( $type )
                LevelUtils::decXp($target, $data[1], [true, false]);
            else 
                LevelUtils::decLevel($target, $data[1], [true, false]);

            $player->sendMessage($target->getName()."님의 ". self::$typeText[$type]."을(를) ".$data[1]."만큼 감소 시켰습니다.");
            

        });

        $form->setTitle("감소");
        $form->addLabel(self::getLabelText($target, $type));
        $form->addInput("감소량을 입력란에 입력해 주세요");

        $form->sendToPlayer($player);

    }

    public static function SubSetForm(Player $player, int $type, Player $target) : void {
        $form = new CustomForm(function(Player $player , ?array $data) use($type, $target) {
            if( is_null($data) )
                return;
            
            if( !is_numeric($data[1]) || $data[1] < 0 ) {
                $player->sendMessage("입력하신 값 ".$data[1]."이 올바르지 않아 작업을 이행할 수 없습니다.");
                return;
            }

            if( $type )
                LevelUtils::setXp($target, $data[1]);
            else 
                LevelUtils::setLevel($target, $data[1]);

            $player->sendMessage($target->getName()."님의 ". self::$typeText[$type]."을(를) ".$data[1]."만큼 설정 시켰습니다.");
        });

        $form->setTitle("설정");
        $form->addLabel(self::getLabelText($target, $type));
        $form->addInput("설정할 값을 입력란에 입력해 주세요.");

        $form->sendToPlayer($player);

    }



}

?>