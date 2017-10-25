<?php
/*
 *     E-cidade Software Publico para Gestao Municipal                
 *  Copyright (C) 2014  DBselller Servicos de Informatica             
 *                            www.dbseller.com.br                     
 *                         e-cidade@dbseller.com.br                   
 *                                                                    
 *  Este programa e software livre; voce pode redistribui-lo e/ou     
 *  modifica-lo sob os termos da Licenca Publica Geral GNU, conforme  
 *  publicada pela Free Software Foundation; tanto a versao 2 da      
 *  Licenca como (a seu criterio) qualquer versao mais nova.          
 *                                                                    
 *  Este programa e distribuido na expectativa de ser util, mas SEM   
 *  QUALQUER GARANTIA; sem mesmo a garantia implicita de              
 *  COMERCIALIZACAO ou de ADEQUACAO A QUALQUER PROPOSITO EM           
 *  PARTICULAR. Consulte a Licenca Publica Geral GNU para obter mais  
 *  detalhes.                                                         
 *                                                                    
 *  Voce deve ter recebido uma copia da Licenca Publica Geral GNU     
 *  junto com este programa; se nao, escreva para a Free Software     
 *  Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA          
 *  02111-1307, USA.                                                  
 *  
 *  Copia da licenca no diretorio licenca/licenca_en.txt 
 *                                licenca/licenca_pt.txt 
 */
require_once(modification("libs/db_stdlib.php"));
require_once(modification("libs/db_utils.php"));
require_once(modification("libs/db_app.utils.php"));
require_once(modification("libs/db_conecta_plugin.php"));
require_once(modification("libs/db_sessoes.php"));
require_once(modification("libs/JSON.php"));
require_once(modification("dbforms/db_funcoes.php"));

$oJson                  = new services_json();  
$oParam                 = $oJson->decode(str_replace("\\","",$_POST["json"]));
$oRetorno               = new stdClass();
$oRetorno->iStatus      = 1;
$oRetorno->sMessage     = '';
$aDadosRetorno          = array();

try {

  db_inicio_transacao();

  switch ($oParam->exec) {

    case 'getMetas':

      $iAcao    = $oParam->iAcao;    
      $oDaoProjativMetas = db_utils::getDao("orcprojativmetas");

      $aMetas = array();
      $iAnoADetalhar = db_getsession('DB_anousu');

      $sCampos = "sequencial, 
      		      orcprojativ, 
      		      meta, 
      		      unidademedida, 
      		      coalesce(observacao, '') as observacao, 
                  coalesce((select valor 
      		                  from plugins.orcprojativmanutencaometas 
      		                 where orcprojativmetas = plugins.orcprojativmetas.sequencial), 0) as valor";
      $sWhere = "anousu = $iAnoADetalhar and orcprojativ = $iAcao";
      
      $sSqlProjativMetas = $oDaoProjativMetas->sql_query_file(null, $sCampos, null, $sWhere);
      //die($sSqlProjativMetas);
      $rsProjativMetas   = $oDaoProjativMetas->sql_record($sSqlProjativMetas);

      if($oDaoProjativMetas->numrows == 0) {
        throw new Exception("A açao selecionada não possui nenhuma meta definida para $iAnoADetalhar.");        
      }

      for ($i = 0; $i < $oDaoProjativMetas->numrows; $i++) { 
        
        $oProjativMeta = db_utils::fieldsMemory($rsProjativMetas, $i);

        $oMeta->sequencial = $oProjativMeta->sequencial;
        $oMeta->orcprojativ = $oProjativMeta->orcprojativ;
        $oMeta->meta = urlencode($oProjativMeta->meta);
        $oMeta->unidademedida = urlencode($oProjativMeta->unidademedida);
        $oMeta->observacao = urlencode($oProjativMeta->observacao);
        $oMeta->valor = $oProjativMeta->valor;
        
        $aMetas[$i] = $oMeta;
        unset($oMeta);
      }

      $oRetorno->aMetas = $aMetas;

      break;

    case 'salvarMetas' :

      $aMetas = $oParam->aMetas;
      $oDaoProjativMetas = db_utils::getDao("orcprojativmetas");
      $oDaoProjativManutencaoMetas = db_utils::getDao("orcprojativmanutencaometas");

      foreach ($aMetas as $oMeta) {
        
      	if (empty($oMeta->observacao) && (empty($oMeta->valor) || $oMeta->valor == 0) ) {
      		continue;
      	}
      	
        //Atualizar observaçao (sempre vai haver dado na tabela de metas)
        $rsProjativMetas = $oDaoProjativMetas->sql_record($oDaoProjativMetas->sql_query_file($oMeta->sequencial));
        
        if ($oDaoProjativMetas->numrows == 0) {
          throw new Exception("Erro ao inserir o detalhamento da meta $oMeta->sequencial.");          
        }

        $oProjativMeta = db_utils::fieldsMemory($rsProjativMetas, 0);

        $sObservacao = urldecode($oMeta->observacao);
        $sSqlUpdate = "update orcprojativmetas set observacao = '{$sObservacao}' 
                        where sequencial = {$oMeta->sequencial}";
        $rsUpdate = db_query($sSqlUpdate);
        if (!$rsUpdate) {
        	throw new Exception("Erro alterando observação da meta.\n\n".pg_last_error());
        }

        //Verificar se existe valor
        $rsManutencaoMetas = $oDaoProjativManutencaoMetas->sql_record($oDaoProjativManutencaoMetas->sql_query_file(null, "*", 
                                                                                            null, "orcprojativmetas = $oMeta->sequencial"));
        //Se não existir, insere
        if($oDaoProjativManutencaoMetas->numrows == 0) {
          
          $oDaoProjativManutencaoMetas->sequencial       = null;
          $oDaoProjativManutencaoMetas->orcprojativmetas = $oMeta->sequencial;
          $oDaoProjativManutencaoMetas->valor            = $oMeta->valor;
          $oDaoProjativManutencaoMetas->incluir(null);
        //Se existir, atualiza
        } else {

          $oManutencaoMetas = db_utils::fieldsMemory($rsManutencaoMetas, 0);
          $oDaoProjativManutencaoMetas->sequencial       = $oManutencaoMetas->sequencial;
          $oDaoProjativManutencaoMetas->orcprojativmetas = $oManutencaoMetas->orcprojativmetas;
          $oDaoProjativManutencaoMetas->valor            = $oMeta->valor;
          $oDaoProjativManutencaoMetas->alterar($oManutencaoMetas->sequencial);

        }

        if ($oDaoProjativManutencaoMetas->erro_status == 0) {
        	throw new Exception("Erro na manutenção dos valores da meta.\n\n".$oDaoProjativManutencaoMetas->erro_msg);
        }

      }
      
      echo pg_last_error();
      $oRetorno->sMessage = "Alteração efetuada com sucesso.";

      break;

  	case 'pesquisarAcoes' :

  	  $iOrgao    = $oParam->iOrgao;
      $iUnidade  = $oParam->iUnidade;
      $iPrograma = $oParam->iPrograma;
      $oDaoProjativUnidade = db_utils::getDao("orcprojativorcunidade");

      $aAcoes = array();
      $aWhere = array();

      $iAnoADetalhar = db_getsession('DB_anousu');
      $aWhere[] = "anousu = $iAnoADetalhar";
      $aWhere[] = "orcprojativ in (select orcprojativ from plugins.orcprojativmetas where anousu = $iAnoADetalhar)";

      if(!empty($iOrgao)) {
        $aWhere[] = "orcorgao = $iOrgao";        
      }

      if(!empty($iUnidade)) {
        $aWhere[] = "orcunidade = $iUnidade";         
      }

      if(!empty($iPrograma)) {
        $aWhere[] = "orcprojativ in (select o55_projativ from orcprojativ inner join orcdotacao where o58_programa = $iPrograma)";        
      }
      $sWhere = implode(" and ", $aWhere);
      
      $sSqlProjativ = "select orcprojativorcunidade.*, 
                              o55_descr,
                              (select count(*) 
                                 from plugins.orcprojativmetas
                                where orcprojativ = o55_projativ
                                  and anousu      = o55_anousu) as qtd_metas,
                              (select count(*) 
                                 from plugins.orcprojativmanutencaometas 
                                      inner join plugins.orcprojativmetas on orcprojativmetas.sequencial = orcprojativmanutencaometas.orcprojativmetas 
                                where orcprojativ = o55_projativ
                                  and anousu      = o55_anousu) as qtd_detalhadas   
                         from plugins.orcprojativorcunidade 
                              inner join orcprojativ on o55_projativ = orcprojativ 
      		                                        and o55_anousu = anousu
      		            where {$sWhere}
      		           order by orcorgao, orcunidade, orcprojativ";
      $rsProjativ   = $oDaoProjativUnidade->sql_record($sSqlProjativ);
      if($oDaoProjativUnidade->numrows == 0) {
        throw new Exception("Nenhuma ação encontrada para os filtros informados.");        
      }

      for ($i = 0; $i < $oDaoProjativUnidade->numrows; $i++) { 
        $oProjativ = db_utils::fieldsMemory($rsProjativ, $i);
        
        $oAcao->anousu = $oProjativ->anousu;
        $oAcao->orcprojativ = $oProjativ->orcprojativ;
        $oAcao->orcorgao = $oProjativ->orcorgao;
        $oAcao->orcunidade = $oProjativ->orcunidade;
        $oAcao->descricao = urlencode($oProjativ->o55_descr);
        $oAcao->qtd_metas = $oProjativ->qtd_metas;
        $oAcao->qtd_detalhadas = $oProjativ->qtd_detalhadas;
        
        $aAcoes[$i] = $oAcao;
        unset($oAcao);
      }
      $oRetorno->aAcoes = $aAcoes;

  	 break;

  }

  db_fim_transacao(false);
  $oRetorno->sMessage = urlencode($oRetorno->sMessage);
  echo $oJson->encode($oRetorno);

} catch (Exception $eErro){

  db_fim_transacao(true);
  $oRetorno->iStatus  = 2;
  $oRetorno->sMessage = urlencode($eErro->getMessage());
  echo $oJson->encode($oRetorno);

}
?>
