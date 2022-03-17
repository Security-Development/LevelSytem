<?php

namespace Neo\utils;

use jojoe77777\FormAPI\CustomForm;
use jojoe77777\FormAPI\SimpleForm;
use pocketmine\player\Player;
use pocketmine\Server;

class FormUtils {

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

        $form = new CustomForm(function(Player $player, ?array $data) use($PlayerData) {
            if( is_null($data) )
                return;
            
                $handlePlayer = Server::getInstance()->getPlayerByPrefix($PlayerData[$data[0]]);

            if( !is_null($data[1]) )
                foreach($PlayerData as $names) 
                    $handlePlayer = ExtendsLib::getPlayerHandle($names, $data[1]);
            
            self::SubLevelForm($player, $type, $handlePlayer);
        });
        
        $typeText = match($type) {
            0 => "레벨",
            1 => "경험치",
            default => null
        };

        if( is_null($typeText) ) 
            return;

        $form->setTitle($typeText." 관리");
        $form->addLabel($typeText."을(를) 관리합니다.");
        $form->addDropdown("관리할 대상이 접속 중이라면 아래에서 선택해 주세요.", $PlayerData, 0);
        $form->addInput("기입란에 입력하여 지정하셔도 됩니다.");

        $form->sendToPlayer($player);

    }

    public static function SubLevelForm(Player $player, int $type, Player $target) : void {
        $form = new SimpleForm(function(Player $player, ?int $data) {
        });

        $form->setTitle("프로세스 목록");
        $form->setContent($target->getName()."님에 대해 진행할 프로세스를 채팅에 입력해 주세요.");
        $form->addButton("증가");
        $form->addButton("감소");
        $form->addButton("설정");

        $form->sendToPlayer($player);

    }

    public static function SubIncForm(Player $player, int $type) : void {
        $form = new CustomForm(function() {

        });

        $form->setTitle("증가");
        $form->addInput("증가량을 입력란에 입력해 주세요");

        $form->sendToPlayer($player);

    }

    public static function SubDecForm(Player $player, int $type) : void {
        $form = new CustomForm(function() {

        });

        $form->setTitle("감소");
        $form->addInput("감소량을 입력란에 입력해 주세요");

        $form->sendToPlayer($player);

    }

    public static function SubSetForm(Player $player, int $type) : void {
        $form = new CustomForm(function() {

        });

        $form->setTitle("설정");
        $form->addInput("설정할 값을 입력란에 입력해 주세요.");

        $form->sendToPlayer($player);

    }



}

?>
