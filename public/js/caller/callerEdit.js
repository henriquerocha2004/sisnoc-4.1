
$(function(){
    var popoverData = null;
    var statusSubcaller = null;

    //Verify if exists return of validation
    if($('#establishment_code').val() !== ''){
        $.get(routes.getLinksEstablishment, {establishment_code: $('#establishment_code').val()}, function(r){
            if(r.response){
                popoverData = r;
                getInfoEstablishment($('#id_link').val());
            }
        });
    }

    console.log($('#next_action').val());

    if($('#next_action').val() != null){
        showInputExtras($('#next_action').val(), 'true');
    }

    //Ação tomada quando o usuário coloca o cursor no status da subchamado
    $(".showSubSaller").hover(function(){
        statusSubcaller = $(this).html();
        $(this).html("Ver");
    },function(){
        $(this).html(statusSubcaller);
    });

    // Instanciando os plugins de data
    $("#hr_up, #deadline").flatpickr({
        enableTime:true,
        dateFormat: "d/m/Y H:i",
        locale: "pt"
    });

    //Ação relacionada ao botão nova nota
    $("#new-note").click(function(){
        $("#content").val("");
        $(this).attr('disabled', true);
        $("#save-note").attr('disabled', false);
    });

    // Ação relacionada ao botão salvar nota
    $("#save-note").click(function(){

        if($("#content").val() != ''){
            var dados = {
                "subcallerId" : $("#lastSubcallerId").val(),
                "content" : $("#content").val()
            }

            $.get(routes.insertNotes, dados, function(response){

                if(response.result){
                    console.log(response);
                    $("#btn-notes").append(
                       `<button type="button" data-id-note="${response.note.id}" class="btn btn-sm btn-success btn-notes mb-2"><i class="fa fa-sticky-note"></i> Nota de ${response.note.user}<br><small>${response.note.created_at}</small></button>`
                    );

                    $("#save-note").attr('disabled', true);
                    $("#new-note").attr('disabled', false);

                }else{
                    $.alert({
                        title: "Aviso | Sisnoc",
                        content: response.message
                    });
                }

            });
        }else{
            $.alert({
                title: "Aviso | Sisnoc",
                content: "Insira alguma informaçao no campo observações"
            })
        }
    });

    // Ação relacionada ao clicar nas notas já criadas na tela.
    $("#btn-notes").on('click', '.btn-notes', function(){
        var idNote = $(this).attr('data-id-note');

        $("#save-note").attr('disabled', true);
        $("#new-note").attr('disabled',false);

        $.get(routes.getNotes, {idnote: idNote}, function(r){
            if(r.response){
                $("#content").val(r.content);
            }else{
                $.alert({
                    title: "Aviso | Sisnoc",
                    content : r.message
                });
            }
        });

    });

    //Ação relacionada ao clicar no botão de anexos
    $(".image").click(function(){
        window.open($(this).attr('data-url-image'), 'photo', 'width=795, height=590, top=100, left=699, scrollbars=no, status=no, toolbar=no, location=no, menubar=no, resizable=no, fullscreen=no')
    });

    //action when the user selects a link
    $("#id_link").change(function(){

        var idLink = $(this).val();

        if(idLink !== ''){
            getInfoEstablishment(idLink);
        }
    });

    //Ação relacionada ao selecionar a ação tomada
    $("#next_action").change(function(){
        var action = $(this).val();
        var subCaller = $(this).attr('data-is-subcaller');

        if(action !== ''){
            showInputExtras(action, subCaller);
        }else{
            $(".extra-input").hide();
        }
    });

    //Ação para visualizar ações anterioes
    $(".showSubSaller").click(function(){
        var url = $(this).attr('data-url-show');
        console.log(url);
        if(url != ''){
            window.location.href = url;
        }

    });


    function showInputExtras(action, subCaller){
        $(".extra-input").hide();

        if(subCaller == 'true'){
            switch(action){
                case '1':
                    $("#divHrUP").show();
                    $("#divCauseProb").show();
                break;
                case '2':
                    $("#divCallTel").show();
                    $("#divDeadLine").show();
                break;
                case '3':
                    $("#divOTRS").show();
                break;
                case '4':
                    $("#divSemep").show();
                break;
                default:
                    $(".extra-input").hide();
                break
            }
        }else{
            switch(action){
                case '1':
                    $("#divHrUP").show();
                    $("#divCauseProb").show();
                break;
                default:
                    $(".extra-input").hide();
                break
            }
        }

    }

    function getInfoEstablishment(idLink){

        $.get(routes.verifyOpenCalled, {id_link: idLink}, function(r){

                //First insert information into popover
                var dataContent =
                `
                <div>
                    <p>Endereço: ${popoverData.establishment.info.address}<p>
                    <p>Cidade: ${popoverData.establishment.info.city}<p>
                    <p>UF: ${popoverData.establishment.info.state}<p>
                    <p>Gerente: ${popoverData.establishment.info.manager_name}<p>
                    <p>Contato Gerente: ${popoverData.establishment.info.manager_contact}<p>
                    <p>Gerente Regional: ${popoverData.establishment.regionalManager.name}<p>
                    <p>Contato Gerente Regional: ${popoverData.establishment.regionalManager.contact}<p>
                </div>
                `;

                $("#btn-popover").attr('data-content', dataContent);
                $("#btn-popover").html('Informações do Estabelecimento');

                $(function () {
                    $('[data-toggle="popover"]').popover({
                        html: true
                    })
                });

                $("#called").show(800);
                $("#btn-save").attr('disabled', false).removeClass('disabled');

        });
    }
});
