<?php
header ("Access-Control-Allow-Origin: *");
header ("Access-Control-Allow-Methods: GET, POST, PATCH, PUT, DELETE, OPTIONS");
header ("Access-Control-Allow-Headers: *");
include_once "core/common.php";

if(isset($_POST["token"])) {
    $token = $_POST["token"];    
}

$method = $_POST["fn"];

switch($method) {
    //LOGIN / LOGOUT
    case "user_login":
        userLogin($_POST["user_email"], $_POST["user_jelszo"]);
    break;
    case "user_logout":
        userLogout();
    break;
    //ADMIN USER
    case "user_add":
        userAdd($_POST["user_nev"], $_POST["user_email"], $_POST["user_jelszo"]);
    break;
    case "user_set_aktiv":
        userSetAktiv($_POST["user_id"], $_POST["user_aktiv"]);
    break;
    case "user_edit":
        userEdit($_POST["user_id"], $_POST["user_nev"], $_POST["user_email"]);
    break;
    case "user_set_password":
        userSetPassword($_POST["user_id"], $_POST["user_jelszo"], $_POST["user_new_jelszo"]);
    break;
    case "user_reset_password":
        userResetPassword($_POST["user_id"], $_POST["user_new_jelszo"]);
    break;
    //TARTALMAK
    case "tartalom_add":
        tartalomAdd($_POST["tartalom_datum"], $_POST["tartalom_cim"], $_POST["tartalom_lead"], $_POST["tartalom_szoveg"], $_POST["tartalom_kep"], $_POST["tartalom_video"], $_POST["tartalom_pdf"], 
                        $_POST["tartalom_statusz"], $_POST["tartalom_elrendezes"]);
    break;
    case "tartalom_set_statusz":
        tartalomSetStatusz($_POST["tartalom_id"], $_POST["tartalom_statusz"]);
    break;
    case "tartalom_set_datum":
        tartalomSetDatum($_POST["tartalom_id"], $_POST["tartalom_datum"]);
    break;
    case "tartalom_set_pdf":
        tartalomSetPdf($_POST["tartalom_id"], $_POST["tartalom_pdf"]);
    break;
    case "tartalom_edit":
        tartalomEdit($_POST["tartalom_id"], $_POST["tartalom_cim"], $_POST["tartalom_lead"], $_POST["tartalom_szoveg"], $_POST["tartalom_kep"], $_POST["tartalom_video"], $_POST["tartalom_statusz"], $_POST["tartalom_elrendezes"]);
    break;
    case "tartalom_add_megtekintes":
        tartalomAddMegtekintes($_POST["tartalom_id"]);
    break;
    case "get_tartalmak":
        getTartalmak();
    break;

    case "get_all_tartalom":
        getAllTartalom();
    break;
    case "update_sorszam":
        sorszamUpdate($_POST["tartalom_id"], $_POST["tartalom_sorszam"]);
    break;
    case "get_tartalom":
        getTartalom($_POST["tartalom_id"]);
    break;
    case "del_tartalom":
        delTartalom($_POST["tartalom_id"]);
    break;
    //KERDOIVEK
    case "kerdoiv_add":
        kerdoivAdd($_POST["kerdoiv_cim"], $_POST["kerdoiv_leiras"], $_POST["kerdoiv_aktiv"]);
    break;
    case "kerdoiv_set_aktiv":
        kerdoivSetAktiv($_POST["kerdoiv_id"], $_POST["kerdoiv_aktiv"]);
    break;
    case "kerdoiv_edit":
        kerdoivEdit($_POST["kerdoiv_id"], $_POST["kerdoiv_cim"], $_POST["kerdoiv_leiras"]);
    break;
    case "kerdoiv_add_kitoltes":
        kerdoivAddKitoltes($_POST["kerdoiv_id"]);
    break;
    case "get_kerdoivek":
        getKerdoivek();
    break;
    case "get_kerdoiv":
        getKerdoiv($_POST["kerdoiv"]);
    break;
    //KERDESEK
    case "kerdes_add":
        kerdesAdd($_POST["kerdoiv_id"], $_POST["kerdes_szoveg"], $_POST["kerdes_aktiv"]);
    break;
    case "kerdes_set_aktiv":
        kerdesSetAktiv($_POST["kerdes_id"], $_POST["kerdes_aktiv"]);
    break;
    case "kerdes_edit":
        kerdesEdit($_POST["kerdes_id"], $_POST["kerdes_szoveg"]);
    break;
    case "get_kerdes":
        getKerdes($_POST["kerdes_id"]);
    break;
    case "get_kerdesek_by_kerdoiv":
        getKerdesekByKerdoiv($_POST["kerdoiv_id"]);
    break;
    //VALASZOK
    case "valasz_add":
        valaszAdd($_POST["kerdes_id"], $_POST["valasz_szoveg"], $_POST["valasz_pont"], $_POST["valasz_aktiv"]);
    break;
    case "valasz_set_aktiv":
        valaszSetAktiv($_POST["valasz_id"], $_POST["valasz_aktiv"]);
    break;
    case "valasz_edit":
        valaszEdit($_POST["valasz_id"], $_POST["valasz_szoveg"], $_POST["valasz_pont"]);
    break;
    case "get_valaszok_by_kerdes":
        getValaszokByKerdes($_POST["kerdes_id"]);
    break;
    //PONTHATAROK
    case "ponthatar_add":
        ponthatarAdd($_POST["kerdoiv_id"], $_POST["pont_hatar"], $_POST["pont_eredmeny"], $_POST["pont_aktiv"]);
    break;
    case "ponthatar_set_aktiv":
        ponthatarSetAktiv($_POST["pont_id"], $_POST["pont_aktiv"]);
    break;
    case "ponthatar_edit":
        ponthatarEdit($_POST["pont_id"], $_POST["pont_hatar"], $_POST["pont_eredmeny"]);
    break;
    case "get_ponthatarok_by_kerdoiv":
        getPonthatarokByKerdoiv($_POST["kerdoiv_id"]);
    break;
    //VERSION
    case "get_version":
        getVersion();
    break;
}

function CheckToken() {
    global $gEnv;
    global $token;
    $User = new CUserAdmin($gEnv);
    if($User->CheckToken($token)) {
        return true;
    }
    else {
        responseError($User->iErrormessage);
        return false;
    }
}

function userLogin($user_email, $user_jelszo) {
    global $gEnv;
    global $token;
    $User = new CUserAdmin($gEnv);
    if($User->Login($user_email, $user_jelszo)) {
        responseOk();
    }
    else {
        responseError($User->iErrormessage);
    }
}

function userLogout() {
    global $gEnv;
    global $token;

    if(CheckToken()) {
        $User = new CUserAdmin($gEnv);
        $user_id = $User->CheckToken($token);
        if($User->LogOut($user_id)) {
            responseOk();
        }
        else {
            responseError($User->iErrormessage);
        }
    }
}

function userAdd($user_nev, $user_email, $user_jelszo) {
    global $gEnv;
    global $token;
    if(CheckToken()) {
        $User = new CUserAdmin($gEnv);
        if($User->Add($user_nev, $user_email, $user_jelszo, true)) {
            responseOk();
        }
        else {
            responseError($User->iErrormessage);
        }
    }
}

function userSetAktiv($user_id, $user_aktiv) {
    global $gEnv;
    global $token;
    if(CheckToken()) {
        $User = new CUserAdmin($gEnv);
        if($user_aktiv) {
            if($User->SetAktiv($user_id)) {
                responseOk();
            }
            else {
                responseError($User->iErrormessage);
            }
        }
        else {
            if($User->SetInaktiv($user_id)) {
                responseOk();
            }
            else {
                responseError($User->iErrormessage);
            }
        }

    }
}

function userEdit($user_id, $user_nev, $user_email) {
    global $gEnv;
    global $token;
    if(CheckToken()) {
        $User = new CUserAdmin($gEnv);
        if($User->Modify($user_id, $user_nev, $user_email)) {
            responseOk();
        }
        else {
            responseError($User->iErrormessage);
        }
    }
}

function userSetPassword($user_id, $user_jelszo, $user_new_jelszo) {
    global $gEnv;
    global $token;
    if(CheckToken()) {
        $User = new CUserAdmin($gEnv);
        if($User->SetPassword($user_id, $user_jelszo, $user_new_jelszo)) {
            responseOk();
        }
        else {
            responseError($User->iErrormessage);
        }
    }
}

function userResetPassword($user_id, $user_new_jelszo) {
    global $gEnv;
    global $token;
    if(CheckToken()) {
        $User = new CUserAdmin($gEnv);
        if($User->ResetPassword($user_id, $user_new_jelszo)) {
            responseOk();
        }
        else {
            responseError($User->iErrormessage);
        }
    }
}

function tartalomAdd($tartalom_datum, $tartalom_cim, $tartalom_lead, $tartalom_szoveg, $tartalom_kep, $tartalom_video, $tartalom_pdf, $tartalom_statusz, $tartalom_elrendezes) {
    global $gEnv;
        $Tartalom = new CTartalom($gEnv);
        if($Tartalom->Add($tartalom_datum, $tartalom_cim, $tartalom_lead, $tartalom_szoveg, $tartalom_kep, $tartalom_video, "generalt pdf", $tartalom_statusz, 0, $tartalom_elrendezes)) {
            responseOk();
        }
        else {
            responseError($Tartalom->iErrormessage);
        }
}

function tartalomSetStatusz($tartalom_id, $tartalom_statusz) {
    global $gEnv;
    global $token;
    if(CheckToken()) {
        $Tartalom = new CTartalom($gEnv);
        if($Tartalom->SetStatusz($tartalom_id, $tartalom_statusz)) {
            responseOk();
        }
        else {
            responseError($Tartalom->iErrormessage);
        }
    }
}

function tartalomSetDatum($tartalom_id, $tartalom_datum) {
    global $gEnv;
    global $token;
    if(CheckToken()) {
        $Tartalom = new CTartalom($gEnv);
        if($Tartalom->SetDatum($tartalom_id, $tartalom_datum)) {
            responseOk();
        }
        else {
            responseError($Tartalom->iErrormessage);
        }
    }
}

function tartalomSetPdf($tartalom_id, $tartalom_pdf) {
    global $gEnv;
    global $token;
    if(CheckToken()) {
        $Tartalom = new CTartalom($gEnv);
        if($Tartalom->SetPdf($tartalom_id, $tartalom_pdf)) {
            responseOk();
        }
        else {
            responseError($Tartalom->iErrormessage);
        }
    }
}

function tartalomEdit($tartalom_id, $tartalom_cim, $tartalom_lead, $tartalom_szoveg, $tartalom_kep, $tartalom_video, $tartalom_statusz, $tartalom_elrendezes) {
    global $gEnv;
    global $token;
        $Tartalom = new CTartalom($gEnv);
        if($Tartalom->Modify($tartalom_id, $tartalom_cim, $tartalom_lead, $tartalom_szoveg, $tartalom_kep, $tartalom_video, $tartalom_statusz, $tartalom_elrendezes)) {
            responseOk();
        }
        else {
            responseError($Tartalom->iErrormessage);
        }
}

function tartalomAddMegtekintes($tartalom_id) {
    global $gEnv;
    global $token;
    if(CheckToken()) {
        $Tartalom = new CTartalom($gEnv);
        if($Tartalom->AddMegtekintes($tartalom_id)) {
            responseOk();
        }
        else {
            responseError($Tartalom->iErrormessage);
        }
    }
}

function getTartalmak() {
    global $gEnv;
    global $token;
    $Tartalom = new CTartalom($gEnv);
    //1 = készítés alatt, 2 = publikált, 3 = inaktív
    $tartalmak = $Tartalom->GetTartalmakByStatusz(2);
    if($tartalmak) {
        responseOk($tartalmak);
    }
    else {
        responseError($Tartalom->iErrormessage);
    }
}

function getAllTartalom() {
    global $gEnv;
    global $token;
    $Tartalom = new CTartalom($gEnv);
    $tartalmak = $Tartalom->GetAllTartalmak();
    if($tartalmak) {
        responseOk($tartalmak);
    }
    else {
        responseError($Tartalom->iErrormessage);
    }
}

function sorszamUpdate($tartalom_id, $sorszam){
    global $gEnv;
        $Tartalom = new CTartalom($gEnv);
        if($Tartalom->updateSorszam($tartalom_id, $sorszam)) {
            responseOk();
        }
        else {
            responseError($Tartalom->iErrormessage);
        }
}

function getTartalom($tartalom_id) {
    global $gEnv;
    global $token;
        $Tartalom = new CTartalom($gEnv);
        $tartalom = $Tartalom->getTartalom($tartalom_id);
        if($tartalom) {
            responseOk($tartalom);
        }
        else {
            responseError($Tartalom->iErrormessage);
        }
}

function delTartalom($tartalom_id) {
    global $gEnv;
    global $token;
        $Tartalom = new CTartalom($gEnv);
        $tartalom = $Tartalom->DelTartalom($tartalom_id);
        if($tartalom) {
            responseOk($tartalom);
        }
        else {
            responseError($Tartalom->iErrormessage);
        }
}

function kerdoivAdd($kerdoiv_cim, $kerdoiv_leiras, $kerdoiv_aktiv) {
    global $gEnv;
    global $token;
    if(CheckToken()) {
        $Kerdoiv = new CKerdoiv($gEnv);
        if($Kerdoiv->Add($kerdoiv_cim, $kerdoiv_leiras, $kerdoiv_aktiv, 0)) {
            responseOk();
        }
        else {
            responseError($Kerdoiv->iErrormessage);
        }
    } 
}

function kerdoivSetAktiv($kerdoiv_id, $kerdoiv_aktiv) {
    global $gEnv;
    global $token;
    if(CheckToken()) {
        $Kerdoiv = new CKerdoiv($gEnv);
        if($kerdoiv_aktiv) {
            if($Kerdoiv->SetAktiv($kerdoiv_id)) {
                responseOk();
            }
            else {
                responseError($Kerdoiv->iErrormessage);
            }
        }
        else {
            if($Kerdoiv->SetInaktiv($kerdoiv_id)) {
                responseOk();
            }
            else {
                responseError($Kerdoiv->iErrormessage);
            }
        }
    } 
}

function kerdoivEdit($kerdoiv_id, $kerdoiv_cim, $kerdoiv_leiras) {
    global $gEnv;
    global $token;
    if(CheckToken()) {
        $Kerdoiv = new CKerdoiv($gEnv);
        if($Kerdoiv->Modify($kerdoiv_id, $kerdoiv_cim, $kerdoiv_leiras)) {
            responseOk();
        }
        else {
            responseError($Kerdoiv->iErrormessage);
        }
    } 
}

function kerdoivAddKitoltes($kerdoiv_id) {
    global $gEnv;
    global $token;
    if(CheckToken()) {
        $Kerdoiv = new CKerdoiv($gEnv);
        if($Kerdoiv->AddKitoltes($kerdoiv_id)) {
            responseOk();
        }
        else {
            responseError($Kerdoiv->iErrormessage);
        }
    }  
}

function getKerdoivek() {
    global $gEnv;
    global $token;
    if(CheckToken()) {
        $Kerdoiv = new CKerdoiv($gEnv);
        $kerdoivek = $Kerdoiv->GetAktivKerdoivek();
        if($kerdoivek) {
            responseOk($kerdoivek);
        }
        else {
            responseError($Kerdoiv->iErrormessage);
        }
    }  
}

function getKerdoiv($kerdoiv_id) {
    global $gEnv;
    global $token;
    if(CheckToken()) {
        $Kerdoiv = new CKerdoiv($gEnv);
        $kerdoiv = $Kerdoiv->GetKerdoiv($kerdoiv_id);
        if($kerdoiv) {
            responseOk($kerdoiv);
        }
        else {
            responseError($Kerdoiv->iErrormessage);
        }
    }  
}

function kerdesAdd($kerdoiv_id, $kerdes_szoveg, $kerdes_aktiv) {
    global $gEnv;
    global $token;
    if(CheckToken()) {
        $Kerdes = new CKerdes($gEnv);
        if($Kerdes->Add($kerdoiv_id, $kerdes_szoveg, $kerdes_aktiv)) {
            responseOk();
        }
        else {
            responseError($Kerdes->iErrormessage);
        }
    }  
}

function kerdesSetAktiv($kerdes_id, $kerdes_aktiv) {
    global $gEnv;
    global $token;
    if(CheckToken()) {
        $Kerdes = new CKerdes($gEnv);
        if($kerdes_aktiv) {
            if($Kerdes->SetAktiv($kerdes_id)) {
                responseOk();
            }
            else {
                responseError($Kerdes->iErrormessage);
            }
        }
        else {
            if($Kerdes->SetInaktiv($kerdes_id)) {
                responseOk();
            }
            else {
                responseError($Kerdes->iErrormessage);
            }
        }
    } 
}

function kerdesEdit($kerdes_id, $kerdes_szoveg) {
    global $gEnv;
    global $token;
    if(CheckToken()) {
        $Kerdes = new CKerdes($gEnv);
        if($Kerdes->Modify($kerdes_id, $kerdes_szoveg)) {
            responseOk();
        }
        else {
            responseError($Kerdes->iErrormessage);
        }
    }  
}

function getKerdes($kerdes_id) {
    global $gEnv;
    global $token;
    if(CheckToken()) {
        $Kerdes = new CKerdes($gEnv);
        $kerdes = $Kerdes->GetKerdes($kerdes_id);
        if($kerdes) {
            responseOk($kerdes);
        }
        else {
            responseError($Kerdes->iErrormessage);
        }
    }  
}

function getKerdesekByKerdoiv($kerdoiv_id) {
    global $gEnv;
    global $token;
    if(CheckToken()) {
        $Kerdes = new CKerdes($gEnv);
        $kerdesek = $Kerdes->GetAktivKerdesekByKerdoiv($kerdoiv_id);
        if($kerdesek) {
            responseOk($kerdesek);
        }
        else {
            responseError($Kerdes->iErrormessage);
        }
    }  
}

function valaszAdd($kerdes_id, $valasz_szoveg, $valasz_pont, $valasz_aktiv) {
    global $gEnv;
    global $token;
    if(CheckToken()) {
        $Valasz = new CValasz($gEnv);
        if($Valasz->Add($kerdes_id, $valasz_szoveg, $valasz_pont, $valasz_aktiv)) {
            responseOk();
        }
        else {
            responseError($Valasz->iErrormessage);
        }
    } 
}

function valaszSetAktiv($valasz_id, $valasz_aktiv) {
    global $gEnv;
    global $token;
    if(CheckToken()) {
        $Valasz = new CValasz($gEnv);
        if($valasz_aktiv) {
            if($Valasz->SetAktiv($valasz_id)) {
                responseOk();
            }
            else {
                responseError($Valasz->iErrormessage);
            }
        }
        else {
            if($Valasz->SetInaktiv($valasz_id)) {
                responseOk();
            }
            else {
                responseError($Valasz->iErrormessage);
            }
        }
    } 
}

function valaszEdit($valasz_id, $valasz_szoveg, $valasz_pont) {
    global $gEnv;
    global $token;
    if(CheckToken()) {
        $Valasz = new CValasz($gEnv);
        if($Valasz->Modify($kerdes_id, $valasz_szoveg, $valasz_pont)) {
            responseOk();
        }
        else {
            responseError($Valasz->iErrormessage);
        }
    } 
}

function getValaszokByKerdes($kerdes_id) {
    global $gEnv;
    global $token;
    if(CheckToken()) {
        $Valasz = new CValasz($gEnv);
        $valaszok = $Valasz->GetAktivValaszokByKerdes($kerdes_id);
        if($valaszok) {
            responseOk($valaszok);
        }
        else {
            responseError($Valasz->iErrormessage);
        }
    } 
}

function ponthatarAdd($kerdoiv_id, $pont_hatar, $pont_eredmeny, $pont_aktiv) {
    global $gEnv;
    global $token;
    if(CheckToken()) {
        $Ponthatar = new CPonthatar($gEnv);
        if($Ponthatar->Add($kerdoiv_id, $pont_hatar, $pont_eredmeny, $pont_aktiv)) {
            responseOk();
        }
        else {
            responseError($Ponthatar->iErrormessage);
        }
    } 
}

function ponthatarSetAktiv($pont_id, $pont_aktiv) {
    global $gEnv;
    global $token;
    if(CheckToken()) {
        $Pont = new CPonthatar($gEnv);
        if($pont_aktiv) {
            if($Pont->SetAktiv($pont_id)) {
                responseOk();
            }
            else {
                responseError($Pont->iErrormessage);
            }
        }
        else {
            if($Pont->SetInaktiv($pont_id)) {
                responseOk();
            }
            else {
                responseError($Pont->iErrormessage);
            }
        }
    } 
}

function ponthatarEdit($pont_id, $pont_hatar, $pont_eredmeny) {
    global $gEnv;
    global $token;
    if(CheckToken()) {
        $Ponthatar = new CPonthatar($gEnv);
        if($Ponthatar->Modify($kerdoiv_id, $pont_hatar, $pont_eredmeny)) {
            responseOk();
        }
        else {
            responseError($Ponthatar->iErrormessage);
        }
    } 
}

function getPonthatarokByKerdoiv($kerdoiv_id) {
    global $gEnv;
    global $token;
    if(CheckToken()) {
        $Ponthatar = new CPonthatar($gEnv);
        $ponthatarok = $Ponthatar->GetAktivPonthatarokByKerdoiv($kerdoiv_id);
        if($ponthatarok) {
            responseOk($ponthatarok);
        }
        else {
            responseError($Ponthatar->iErrormessage);
        }
    } 
}

function getVersion() {
    global $gEnv;
    $Version = new CVersion($gEnv);
    $version = $Version->checkVersion();
    if($version) {
        responseOk($version);
    }
    else {
        responseError($Version->iErrormessage);
    }
}


function responseOk($responseArray = array()) {
    http_response_code(200);

    if(!empty($responseArray)) {
        echo json_encode($responseArray);
    }
}

function responseError($responseArray = array()) {
    http_response_code(400);

    echo json_encode($responseArray);

}


$gEnv->close();
ob_end_flush();
?>