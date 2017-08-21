/**
 * Conjunto de ações para verificar pedidos.
 * @type {{numero: string}} memoriza o último pedido selecionado.
 */
var Pedido = {
    numero: '',
    click: function (field) {
        field.closest('.form-group')
            .removeClass('has-error has-success')
            .find('.error-message').remove();
    },
    validar: function () {

        $('#nome, #email, #titulo, #observacao').val('');

        Pedido.numero = $('#pedido').val();

        if (Pedido.numero == '') {
            $('#pedido').focus();
        } else {

            $.ajax({
                type: 'get',
                url: 'pedido/validacao/' + Pedido.numero
            }).done(function (response) {

                var hasClass = (response.valido == 1) ? 'has-success' : 'has-error';
                var messageHtml = [
                    '<div class="error-message">',
                    '<span class="fa fa-warning"></span> ',
                    'Número de pedido inexistente',
                    '</div>'
                ].join('');

                var hasMessage = (response.valido == 1) ? '' : messageHtml;

                $('#validaPedido')
                    .closest('.form-group')
                    .addClass(hasClass)
                    .append(hasMessage);

                if (response.valido == 1) {
                    $('#nome').val(response.nome);
                    $('#email').val(response.email);
                    $('form input, form button, form textarea').prop('disabled', false);
                    $('#email').focus();
                } else {
                    $('#pedido').focus();
                }
            }).fail(function (response) {
                alert('Falha ao verificar o pedido');
            });
        }
    },
    revalidar: function () {
        if ($('#pedido').val() !== Pedido.numero) {
            $('form input, form button, form textarea').prop('disabled', true);
            $('#pedido, #validaPedido').prop('disabled', false).focus();
            Pedido.validar();
            $('#pedido').focus();
        }
    }
};

/**
 * Conjunto de ações para tratar dos chamados.
 */
var Chamado = {

    buscar: function (url, filtro) {

        filtro = (undefined == filtro) ? '' : filtro;

        if (url == undefined && filtro == '') {
            $('#relatorioContainer').html([
                '<span class="loading">',
                '<i class="fa fa-refresh fa-spin fa-fw"></i> ',
                'Carregando chamados...',
                '</span>'
            ].join(''));
        }

        $.ajax({
            type: 'get',
            url: (url == undefined) ? 'chamados' : url,
            data: {
                filtro: filtro
            }
        }).done(function (response) {
            $('#relatorioContainer').html(response);
        }).fail(function (response) {
            alert('Falha ao obter os chamados.')
        });
    },
    paginar: function (link) {
        Chamado.buscar(link.prop('href'), $('#filtro').val());
    },
    salvar: function () {

        $('.form-group').removeClass('has-error');
        $('.form-group').find('.error-message').remove();

        $.ajax({
            type: 'POST',
            url: 'chamados/create',
            data: $('form').serialize()
        }).done(function (response) {
            var errors = 0;
            $.each(response.errors, function (key, value) {
                var messageHtml = [
                    '<div class="error-message">',
                    '<span class="fa fa-warning"></span> ',
                    value,
                    '</div>'
                ].join('');

                $('#' + key).closest('.form-group')
                    .addClass('has-error')
                    .append(messageHtml);

                $('#' + key).bind('click keyup keypres', function () {
                    $(this).closest('.form-group').removeClass('has-error');
                    $(this).closest('.form-group').find('.error-message').remove();
                });
                errors++;
            });

            if (errors == 0) {

                $('#pedido, #nome, #email, #titulo, #observacao').val('');
                $('form input, form button, form textarea').prop('disabled', true);
                $('#pedido, #validaPedido').prop('disabled', false).focus();
                $('#pedido').closest('.form-group').removeClass('has-success');

                $('#modal').modal();
                $('#modal').on('shown.bs.modal', function () {
                    $('#closeModal').focus()
                });
            }
        }).fail(function (response) {
            alert('Falha ao registrar o chamado');
        });
    }
};

/**
 * Conjunto de ações para tela de atendimentos.
 */
var Sac = {
    toggle: function (btn) {

        $('#filtro').val('');

        $('#toolbarAcoes button').removeClass('active');

        btn.addClass('active');

        var action = btn.data('action');
        var current = '#' + action + 'Container';
        var previous = '#' + ( (action == 'cadastro') ? 'relatorio' : 'cadastro') + 'Container';

        $(previous).hide();
        $(current).show();

        if (action == 'relatorio') {
            Chamado.buscar(undefined, $('#filtro').val());
        }
    }
};

/**
 * Funcionalidades da página
 */
jQuery(document).ready(function () {

    Pedido.numero = '';

    $(document).on('click', '#toolbarAcoes button', function (e) {
        e.stopImmediatePropagation();
        Sac.toggle($(this));
    });

    $(document).on('click', '.pagination a', function (e) {
        e.preventDefault();
        Chamado.paginar($(this));
    });

    $(document).on('click', '#filtroButton', function (e) {
        e.preventDefault();
        Chamado.buscar(undefined, $('#filtro').val());
    });

    $(document).on('click keyup keypress', '#pedido', function (e) {
        e.stopImmediatePropagation();
        Pedido.click($(this));
    });

    $(document).on('click', '#nome, #email, #titulo, #observacao', function (e) {
        e.stopImmediatePropagation();
        Pedido.revalidar();
    });

    $(document).on('click', '#validaPedido', function (e) {
        e.stopImmediatePropagation();
        Pedido.validar();
    });

    $(document).on('click', '#salvarChamado', function (e) {
        e.stopImmediatePropagation();
        Chamado.salvar();
    })
});