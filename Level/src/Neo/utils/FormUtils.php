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

            self::LevelForm($player);
        });

        $form->setTitle("레벨 시스템 관리");
        $form->setContent("진행할 프로세스를 채팅에 선택해 주세요.");
        $form->addButton("레벨");
        $form->addButton("경험치");
        $form->addButton("배율 티켓");
        $form->addButton("서버 배율");

        $form->sendToPlayer($player);
    }

    public static function LevelForm(Player $player) : void {
        $form = new CustomForm(function(Player $player, ?array $data) {
            if( is_null($data) )
                return;
        });

        $PlayerData = [];

        foreach(Server::getInstance()->getOnlinePlayers() as $players) {
            $PlayerData[] = $players->getName();
        }

        $form->setTitle("레벨 관리");
        $form->addLabel("레벨을 관리합니다.");
        $form->addDropdown("관리할 대상이 접속 중이라면 아래에서 선택해 주세요.", $PlayerData);
        $form->addInput("기입란에 입력하여 지정하셔도 됩니다.");

        $form->sendToPlayer($player);

    }
}

?>