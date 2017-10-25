<?
/*
 *     E-cidade Software Publico para Gestao Municipal
 *  Copyright (C) 2014  DBSeller Servicos de Informatica
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
require_once(modification("libs/db_conecta_plugin.php"));
require_once(modification("libs/db_sessoes.php"));
require_once(modification("libs/db_usuariosonline.php"));
require_once(modification("dbforms/db_funcoes.php"));
require_once(modification("libs/db_sql.php"));
require_once(modification("libs/db_app.utils.php"));

$iInstit = db_getsession("DB_instit");

?>
<html>
<head>
<style type=""></style>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
<?php
  db_app::load("scripts.js");
  db_app::load("dbtextField.widget.js");
  db_app::load("prototype.js");
  db_app::load("datagrid.widget.js");
  db_app::load("DBLancador.widget.js");
  db_app::load("strings.js");
  db_app::load("grid.style.css");
  db_app::load("estilos.css");
  db_app::load("widgets/windowAux.widget.js");
  db_app::load("widgets/dbmessageBoard.widget.js");
  db_app::load("dbcomboBox.widget.js");
  db_app::load("widgets/DBAncora.widget.js");
  db_app::load("dbtextFieldData.widget.js");
?>

</head>
<body bgcolor=#CCCCCC bgcolor="#CCCCCC"  >

<center>
<form name="form1" method="post">

    <fieldset style="margin-top: 50px; width: 600px;">
    <legend><strong>Manutenção das Metas</strong></legend>
      <table border="0" align='left' >
        <tr>
          <td nowrap title="orgao">
             <?
              db_ancora("Órgão: ","js_pesquisao40_orgao(true);",1);
             ?>
          </td>
          <td> 
            <?
              db_input('o40_orgao',10,'',true,'text',1," onchange='js_pesquisao40_orgao(false);'");
              db_input('o40_descr',40,'',true,'text',3,'')
            ?>
          </td>
        </tr>
        <tr>
          <td nowrap title="unidade">
             <?
              db_ancora("Unidade: ","js_pesquisao41_unidade(true);",1);
             ?>
          </td>
          <td> 
            <?
              db_input('o41_unidade',10,'',true,'text',1," onchange='js_pesquisao41_unidade(false);'");
              db_input('o41_descr',40,'',true,'text',3,'')
            ?>
          </td>
        </tr>
        <tr>
          <td nowrap title="programa">
            <?
              db_ancora("Programa: ","js_pesquisao54_programa(true);",1);
            ?>
          </td>
          <td> 
            <?
              db_input('o54_programa',10,'',true,'text',1," onchange='js_pesquisao54_programa(false);'")
            ?>
            <?
              db_input('o54_descr',40,'',true,'text',3);
            ?>
          </td>
        </tr>
      </table>
    </fieldset>
    <div style="margin-top: 10px;">
      <input type="button" id="pesquisar"  value="Pesquisar" onclick="js_pesquisar();">
    </div>

<fieldset style="margin-top: 10px; width: 900px">
  <legend>
    <strong>
      Ações
    </strong>
  </legend>
  <table border="0">
    <tr>
      <td>
        <div id='ctnGridAcoes' ></div>
      </td>
    </tr>
  </table>
</fieldset>


</form>
</center>
<?
db_menu(db_getsession("DB_id_usuario"), db_getsession("DB_modulo"), db_getsession("DB_anousu"), db_getsession("DB_instit"));
?>
</body>
</html>

<script>
var iAno = <?=db_getsession("DB_anousu")?> 
	
window.onload = function() {
  js_pesquisar();
}  

function js_pesquisao40_orgao(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo','db_iframe_orcorgao','func_orcorgao.php?funcao_js=parent.js_mostraorcorgao1|o40_orgao|o40_descr','Pesquisa',true);
  }else{
     if(document.form1.o40_orgao.value != ''){ 
        js_OpenJanelaIframe('top.corpo','db_iframe_orcorgao','func_orcorgao.php?pesquisa_chave='+document.form1.o40_orgao.value+'&funcao_js=parent.js_mostraorcorgao','Pesquisa',false);
     }else{
       document.form1.o40_descr.value = ''; 
     }
  }
}
function js_mostraorcorgao(chave,erro){
  document.form1.o40_descr.value = chave; 
  if(erro==true){ 
    document.form1.o40_orgao.focus(); 
    document.form1.o40_orgao.value = ''; 
  }
}
function js_mostraorcorgao1(chave1,chave2){
  document.form1.o40_orgao.value = chave1;
  document.form1.o40_descr.value = chave2;
  db_iframe_orcorgao.hide();
}

function js_pesquisao41_unidade(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo','db_iframe_orcunidade','func_orcunidade.php?funcao_js=parent.js_mostraorcunidade1|o41_unidade|o41_descr','Pesquisa',true);
  }else{
     if(document.form1.o41_unidade.value != ''){ 
        js_OpenJanelaIframe('top.corpo','db_iframe_orcunidade','func_orcunidade.php?pesquisa_chave='+document.form1.o41_unidade.value+'&funcao_js=parent.js_mostraorcunidade','Pesquisa',false);
     }else{
       document.form1.o41_descr.value = ''; 
     }
  }
}
function js_mostraorcunidade(chave,erro){
  document.form1.o41_descr.value = chave; 
  if(erro==true){ 
    document.form1.o41_unidade.focus(); 
    document.form1.o41_unidade.value = ''; 
  }
}
function js_mostraorcunidade1(chave1,chave2){
  document.form1.o41_unidade.value = chave1;
  document.form1.o41_descr.value = chave2;
  db_iframe_orcunidade.hide();
}
function js_pesquisao41_unidade(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo','db_iframe_orcunidade','func_orcunidade.php?funcao_js=parent.js_mostraorcunidade1|o41_orgao|o41_descr','Pesquisa',true);
  }else{
     if(document.form1.o41_unidade.value != ''){ 
        js_OpenJanelaIframe('top.corpo','db_iframe_orcunidade','func_orcunidade.php?pesquisa_chave='+document.form1.o41_unidade.value+'&funcao_js=parent.js_mostraorcunidade','Pesquisa',false);
     }else{
       document.form1.o41_descr.value = ''; 
     }
  }
}
function js_mostraorcunidade(chave,erro){
  document.form1.o41_descr.value = chave; 
  if(erro==true){ 
    document.form1.o41_unidade.focus(); 
    document.form1.o41_unidade.value = ''; 
  }
}
function js_mostraorcunidade1(chave1,chave2){
  document.form1.o41_unidade.value = chave1;
  document.form1.o41_descr.value = chave2;
  db_iframe_orcunidade.hide();
}
function js_pesquisao41_unidade(mostra) {
  
  if ($F('o40_orgao') == '') {
    
    alert('Antes de escolher uma Unidade, informe um orgão!');
    return false;
    
  } 
  var sFiltro = 'orgao='+$F('o40_orgao');
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo',
                        'db_iframe_orcunidade',
                        'func_orcunidade.php?funcao_js=parent.js_mostraorcunidade1|o41_unidade|o41_descr&'+sFiltro,
                        'Unidades',
                        true
                       );
  }else{
  
     if(document.form1.o41_unidade.value != ''){ 
        js_OpenJanelaIframe('top.corpo',
                            'db_iframe_orcunidade',
                            'func_orcunidade.php?pesquisa_chave='+
                             document.form1.o41_unidade.value+'&funcao_js=parent.js_mostraorcunidade&'+sFiltro,
                            'Pesquisa',
                            false);
     }else{
       document.form1.o41_descr.value = ''; 
     }
  }
}
function js_mostraorcunidade(chave,erro){
  document.form1.o41_descr.value = chave; 
  if(erro==true){ 
    document.form1.o41_unidade.focus(); 
    document.form1.o41_unidade.value = ''; 
  }
}
function js_mostraorcunidade1(chave1,chave2){
  document.form1.o41_unidade.value = chave1;
  document.form1.o41_descr.value = chave2;
  db_iframe_orcunidade.hide();
}

function js_pesquisao54_programa(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo','db_iframe_orcprograma','func_orcprograma.php?funcao_js=parent.js_mostraorcprograma1|o54_programa|o54_descr','Pesquisa',true);
  }else{
     if(document.form1.o54_programa.value != ''){ 
        js_OpenJanelaIframe('top.corpo','db_iframe_orcprograma','func_orcprograma.php?pesquisa_chave='+document.form1.o54_programa.value+'&funcao_js=parent.js_mostraorcprograma','Pesquisa',false);
     }else{
       document.form1.o54_descr.value = ''; 
     }
  }
}
function js_mostraorcprograma(chave,erro){
  document.form1.o54_descr.value = chave; 
  if(erro==true){ 
    document.form1.o54_programa.focus(); 
    document.form1.o54_programa.value = ''; 
  }
}
function js_mostraorcprograma1(chave1,chave2){
  document.form1.o54_programa.value = chave1;
  document.form1.o54_descr.value = chave2;
  db_iframe_orcprograma.hide();
}

var sUrlRPC = "orc4_detalhamentometasPPA.RPC.php";

//================== Pesquisar Registros ===================//

function js_pesquisar(){

  var iOrgao       = $F('o40_orgao');
  var iUnidade     = $F('o41_unidade');
  var iPrograma    = $F('o54_programa');

  var oParam           = new Object();
      oParam.exec      = 'pesquisarAcoes';
      oParam.iOrgao    = iOrgao;
      oParam.iUnidade  = iUnidade;
      oParam.iPrograma = iPrograma;

  js_divCarregando("Pesquisando ações...", 'msgBox');

  var sParam  = js_objectToJson(oParam);
  var oAjax   = new Ajax.Request(
                         'orc4_detalhamentometasPPA.RPC.php',
                         {
                          method    : 'post',
                          parameters: 'json='+sParam,
                          onComplete: js_retornoPesquisa
                          }
                        );
}

function js_retornoPesquisa(oAjax){

    js_removeObj('msgBox');
    var oRetorno = eval("("+oAjax.responseText+")");
    oGridAcoes.clearAll(true);

    if (oRetorno.aAcoes.length > 0) {

        oRetorno.aAcoes.each(function (oDado, iInd) {

          var aRow   = new Array();
 
          aRow[0] = oDado.anousu; // exercicio
          aRow[1] = oDado.orcorgao; //orgao
          aRow[2] = oDado.orcunidade; // unidade
          aRow[3] = oDado.orcprojativ; // codigo acao
          aRow[4] = oDado.descricao.urlDecode(); // descricao
          aRow[5] = oDado.qtd_metas; // qtd de metaas
          aRow[6] = oDado.qtd_detalhadas; // qtd de metas detalhadas
          aRow[7] = "<input type='button' value='Detalhar' onclick='js_criaJanelaDetalhes("+oDado.orcprojativ+");'  ";
  
          oGridAcoes.addRow(aRow);
        });
        oGridAcoes.renderRows();
    } else {
      alert("Nenhuma ação encontrada.");
      return false;
    }

} 


//================== Retorna detalhamento da ação selecionada ============//

function js_getMetas(iAcao) {

    var oParametros = new Object();

    js_divCarregando("Buscando detalhamentos das metas",'msgBox');

    oParametros.exec  = "getMetas";
    oParametros.iAcao = iAcao;
    new Ajax.Request(sUrlRPC,
                    {method: "post",
                     parameters:'json='+Object.toJSON(oParametros),
                     onComplete: js_retornoGetMetas
                    });
}

function js_retornoGetMetas(oAjax){

    js_removeObj('msgBox');
    var oRetorno = eval("("+oAjax.responseText+")");
    oGridDetalhamentoMetas.clearAll(true);

    if (oRetorno.aMetas.length > 0) {

        oRetorno.aMetas.each(function (oDado, iInd) {

          var nValor = '';
          if (oDado.valor != 0) {
              nValor = js_formatar(oDado.valor, 'f');
          }    
          
          var aRow   = new Array();

          aRow[0] = oDado.sequencial;
          aRow[1] = oDado.meta.urlDecode().toUpperCase();
          aRow[2] = oDado.unidademedida.urlDecode().toUpperCase();
          
          aRow[3]  = "<input type = 'text' id='obs_" + oDado.sequencial + "' size='190px' style='text-transform:uppercase;width:100%;height:100%;text-align:left;border:1px inset'";
          aRow[3] += " value = '"+oDado.observacao.urlDecode().toUpperCase()+"' >";

          aRow[4]  = "<input type = 'text' id='valor_" + oDado.sequencial + "' size='90px' style='width:100%;height:100%;text-align:right;border:1px inset'";
          aRow[4] += " class='valores' onkeypress='return js_teclas(event,this)' value='"+nValor+"' >";

          oGridDetalhamentoMetas.addRow(aRow);
        });
        oGridDetalhamentoMetas.renderRows();
    } else {
      alert("Nenhuma ação encontrada.");
      return false;
    }

}

//================== Persiste as Metas no Banco ============//

function js_salvarMetas(iAcao){
    
    var oParametros        = new Object();
        oParametros.exec   = 'salvarMetas';
        oParametros.iAcao  = iAcao;
        oParametros.aMetas = new Array();


    oGridDetalhamentoMetas.aRows.each(function (oRow, iIndice) {

      var oMeta        = new Object();

      oMeta.sequencial = oRow.aCells[0].getValue();
      oMeta.observacao = encodeURIComponent(oRow.aCells[3].getValue());
      oMeta.valor      = js_formatar(oRow.aCells[4].getValue(),'f').replace(",", ".");
      
      oParametros.aMetas.push(oMeta);
    });

    js_divCarregando("Salvando detalhamento das metas...", 'msgBox');

    new Ajax.Request(sUrlRPC,
                            {method: "post",
                             parameters:'json='+Object.toJSON(oParametros),
                             onComplete: js_retornoSalvarMetas
                            });
}

function js_retornoSalvarMetas(oAjax) {

    js_removeObj('msgBox');
    var oRetorno = eval("("+oAjax.responseText+")");
    js_pesquisar();
    alert(oRetorno.sMessage.urlDecode());
    windowDetalhes.hide();
    
}


//===================== Grid que conterá as ações a serem detalhadas ==========//

function js_criaGridAcoes() {

  oGridAcoes = new DBGrid('oGridAcoes');
  oGridAcoes.nameInstance = 'oGridAcoes';

  oGridAcoes.setCellWidth(new Array( '60px' ,
                                     '60px',
                                     '60px',
                                     '80px',
                                     '300px',
                                     '80px',
                                     '80px',
                                     '80px'));
  
  oGridAcoes.setCellAlign(new Array( 'center',
                                     'center',
                                     'center',
                                     'center',
                                     'left',
                                     'center',
                                     'center',
                                     'center'));
  
  oGridAcoes.setHeader(new Array( 'Exercício',
                                  'Órgão',
                                  'Unidade',
                                  'Ação',
                                  'Descrição',
                                  'Qtd. Metas',
                                  'Qtd. Detalhadas',
                                  'Detalhar'));
  
  oGridAcoes.setHeight(150);
  oGridAcoes.show($('ctnGridAcoes'));
  oGridAcoes.clearAll(true);
}


js_criaGridAcoes();

//===================== chamada de funçoes da janela de metas ==========//
function js_criaJanelaDetalhes(iAcao) {

  if ( $('sTituloWindow') &&  $('sTituloWindow').innerHTML != '' ) {
    $('sTituloWindow').innerHTML = '';
  }

  js_viewMetas(iAcao);
  js_criaGridMetas();
  js_getMetas(iAcao);
  
}

//===================== Grid que conterá as metas da ação ==========//

function js_criaGridMetas() {

    oGridDetalhamentoMetas = new DBGrid('oGridDetalhamentoMetas');
    oGridDetalhamentoMetas.nameInstance = 'oGridDetalhamentoMetas';
    oGridDetalhamentoMetas.setCellWidth(['0px',
                                         '450px',
                                         '100px',
                                         '110px',
                                         '110px']);
    oGridDetalhamentoMetas.setCellAlign(['center',
                                         'left',
                                         'left',
                                         'center',
                                         'center']);
    oGridDetalhamentoMetas.setHeader(['sequencial',
                                      'Meta',    
                                      'Unidade',               
                                      'Especificação',               
                                      'Meta '+iAno]);     

    oGridDetalhamentoMetas.hasTotalizador = false;
    oGridDetalhamentoMetas.setHeight(150);

    oGridDetalhamentoMetas.aHeaders[0].lDisplayed  = false;
           
    oGridDetalhamentoMetas.show($('ctnGridAcoesDetalhes'));
    oGridDetalhamentoMetas.clearAll(true);
}

//================== Janela para detalhar metas ============//
function js_viewMetas (iAcao) {

    var iLarguraJanela = 870;
    var iAlturaJanela  = 350;

    if (typeof(windowDetalhes) != 'undefined' && windowDetalhes instanceof windowAux) {
      windowDetalhes.destroy();
    }

    windowDetalhes   = new windowAux( 'windowDetalhes',
                                      'Detalhamento da Ação',
                                      iLarguraJanela,
                                      iAlturaJanela
                                      );
    

    var sConteudoDetalhes  = "<div>";
        sConteudoDetalhes += "<div id='sTituloWindow'></div> "; 

        sConteudoDetalhes += "  <center>  <br>";
        sConteudoDetalhes += "  <fieldset style='width: 95%;'><legend><strong> Detalhamento da Ação </strong></legend>";
        sConteudoDetalhes += "    <table border = 0 align='left'>  ";

        sConteudoDetalhes += "      <tr nowrap>     ";
        sConteudoDetalhes += "        <td style='width:130px'>   ";
        sConteudoDetalhes += "         <strong>Código da Ação: " + iAcao + " </strong>  ";
        sConteudoDetalhes += "        </td>  ";
        sConteudoDetalhes += "      </tr>    ";

        sConteudoDetalhes += "      <tr>  ";
        sConteudoDetalhes += "        <td>  ";
        sConteudoDetalhes += "          <div id='ctnGridAcoesDetalhes'></div> ";
        sConteudoDetalhes += "        </td> ";
        sConteudoDetalhes += "      </tr> ";

        sConteudoDetalhes += "    </table> ";
        sConteudoDetalhes += "  </fieldset> ";

        sConteudoDetalhes += "<div style='margin-top:10px;'>";
        sConteudoDetalhes += " <input type='button' value='Salvar' id = 'salvar' onclick='js_salvarMetas(" + iAcao + ");' />";
        sConteudoDetalhes += "</div>"  ;

        sConteudoDetalhes += "  </center> ";

        sConteudoDetalhes += "</div>";

    windowDetalhes.setContent(sConteudoDetalhes);
    windowDetalhes.allowCloseWithEsc(false);

    //funcao para corrigir a exibição do window aux, apos fechar a primeira vez
    //windowDetalhes.setShutDownFunction(function () {
    //
    //  windowDetalhes.destroy();
    //  js_pesquisar();
    //  delete windowDetalhes;
    //});

    windowDetalhes.show();
}

</script>
