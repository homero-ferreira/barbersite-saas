import { createApp, h, onMounted, onUnmounted, ref } from 'vue/dist/vue.esm-bundler.js';

const app = createApp({
    template: '<h1>Vue.js funcionando!</h1>'
});

if (document.getElementById('vue-app')) {
    app.mount('#vue-app');
}

// Aplicação Vue exclusiva do site público do cliente
const elementoSiteCliente = document.getElementById('cliente-site-vue');

if (elementoSiteCliente) {
    createApp({
        setup() {
            const menuAberto = ref(false);

            const corPrincipal = elementoSiteCliente.dataset.corPrincipal || '#0066FF';
            const corSecundaria = elementoSiteCliente.dataset.corSecundaria || '#071D49';

            const links = [
                { nome: 'Início', url: '#inicio' },
                { nome: 'Serviços', url: '#produtos' },
                { nome: 'Sobre nós', url: '#sobre' },
                { nome: 'Nossos Trabalhos', url: '#trabalhos' },
                { nome: 'Onde estamos', url: '#contato' },
                { nome: 'Contato', url: '#contato' },
            ];

            return {
                menuAberto,
                corPrincipal,
                corSecundaria,
                links,
            };
        },
    }).mount(elementoSiteCliente);
}

// Máscaras de campos reutilizáveis
function aplicarMascara(valor, tipo) {
    const numeros = valor.replace(/\D/g, '');

    switch (tipo) {
        case 'cpf':
            return numeros.slice(0, 11)
                .replace(/(\d{3})(\d)/, '$1.$2')
                .replace(/(\d{3})(\d)/, '$1.$2')
                .replace(/(\d{3})(\d{1,2})$/, '$1-$2');

        case 'cnpj':
            return numeros.slice(0, 14)
                .replace(/^(\d{2})(\d)/, '$1.$2')
                .replace(/^(\d{2})\.(\d{3})(\d)/, '$1.$2.$3')
                .replace(/\.(\d{3})(\d)/, '.$1/$2')
                .replace(/(\d{4})(\d{1,2})$/, '$1-$2');

        case 'documento':
            return aplicarMascara(numeros, numeros.length > 11 ? 'cnpj' : 'cpf');

        case 'telefone':
        case 'whatsapp':
            return numeros.slice(0, 11)
                .replace(/^(\d{2})(\d)/, '($1) $2')
                .replace(/(\d{4,5})(\d{4})$/, '$1-$2');

        case 'cep':
            return numeros.slice(0, 8)
                .replace(/^(\d{5})(\d)/, '$1-$2');

        default:
            return valor;
    }
}

// Aplica as máscaras aos campos identificados com data-mascara
function atualizarMascara(campo) {
    const tipo = campo.dataset.mascara;

    if (!tipo) return;

    campo.value = aplicarMascara(campo.value, tipo);
}

document.addEventListener('input', function (evento) {
    const campo = evento.target;

    if (campo instanceof HTMLInputElement && campo.matches('[data-mascara]')) {
        atualizarMascara(campo);
    }
});

document.addEventListener('DOMContentLoaded', function () {
    document.querySelectorAll('input[data-mascara]').forEach(atualizarMascara);
});

// Abas do formulário completo de edição
function ativarAbaUsuario(formulario, aba = 'usuario') {
    formulario.querySelectorAll('[data-conteudo-aba]').forEach(painel => {
        const ativa = painel.dataset.conteudoAba === aba;

        painel.classList.toggle('hidden', !ativa);
        painel.classList.toggle('grid', ativa && ['usuario', 'empresa', 'redes'].includes(aba));
    });

    formulario.querySelectorAll('[data-aba-usuario]').forEach(botao => {
        const ativo = botao.dataset.abaUsuario === aba;

        botao.classList.toggle('bg-gray-900', ativo);
        botao.classList.toggle('text-white', ativo);
        botao.classList.toggle('text-gray-700', !ativo);
        botao.setAttribute('aria-selected', String(ativo));
    });
}

function habilitarEdicaoUsuario(formulario) {
    formulario.querySelector('#abas-edicao-usuario')?.classList.remove('hidden');
    ativarAbaUsuario(formulario, 'usuario');
}

function configurarSelecaoProdutos(formulario) {
    formulario.querySelectorAll('[data-produtos-acao]').forEach(botao => {
        botao.addEventListener('click', () => {
            const selecionar = botao.dataset.produtosAcao === 'todos';

            formulario.querySelectorAll('[data-produto-exibir]').forEach(campo => {
                campo.checked = selecionar;
            });

            if (!selecionar) {
                formulario.querySelectorAll('[data-produto-destaque]').forEach(campo => {
                    campo.checked = false;
                });
            }
        });
    });

    formulario.querySelectorAll('[data-produto-exibir]').forEach(campo => {
        campo.addEventListener('change', () => {
            if (!campo.checked) {
                const destaque = formulario.querySelector(
                    `[data-produto-destaque][value="${campo.value}"]`
                );

                if (destaque) destaque.checked = false;
            }
        });
    });

    formulario.querySelectorAll('[data-produto-destaque]').forEach(campo => {
        campo.addEventListener('change', () => {
            if (campo.checked) {
                const exibir = formulario.querySelector(
                    `[data-produto-exibir][value="${campo.value}"]`
                );

                if (exibir) exibir.checked = true;
            }
        });
    });
}

function restaurarSelecaoProdutos(formulario, usuario) {
    const converterIds = valor => new Set(
        String(valor ?? '')
            .split(',')
            .map(id => Number(id.trim()))
            .filter(id => Number.isInteger(id) && id > 0)
    );

    const removidos = converterIds(usuario.produtos_removidos);
    const destaques = converterIds(usuario.produtos_destaque);

    formulario.querySelectorAll('[data-produto-exibir]').forEach(campo => {
        campo.checked = !removidos.has(Number(campo.value));
    });

    formulario.querySelectorAll('[data-produto-destaque]').forEach(campo => {
        const id = Number(campo.value);

        campo.checked = destaques.has(id) && !removidos.has(id);
    });
}

function configurarImagensUsuario(formulario) {
    formulario.querySelectorAll('[data-imagem-upload]').forEach(campo => {
        campo.addEventListener('change', () => {
            const arquivo = campo.files?.[0];

            if (!arquivo) return;

            if (!['image/jpeg', 'image/png', 'image/webp'].includes(arquivo.type)) {
                alert('Selecione uma imagem JPG, PNG ou WebP.');
                campo.value = '';
                return;
            }

            if (arquivo.size > 3 * 1024 * 1024) {
                alert('A imagem deve ter no máximo 3 MB.');
                campo.value = '';
                return;
            }

            const imagem = formulario.querySelector(
                `[data-imagem-previa="${campo.dataset.imagemUpload}"]`
            );

            const vazio = formulario.querySelector(
                `[data-imagem-vazia="${campo.dataset.imagemUpload}"]`
            );

            if (!imagem) return;

            const enderecoAnterior = imagem.dataset.enderecoTemporario;

            if (enderecoAnterior) {
                URL.revokeObjectURL(enderecoAnterior);
            }

            const endereco = URL.createObjectURL(arquivo);

            imagem.src = endereco;
            imagem.dataset.enderecoTemporario = endereco;
            imagem.classList.remove('hidden');

            if (vazio) vazio.classList.add('hidden');

            if (campo.dataset.imagemUpload === 'banner_institucional_imagem') {
                atualizarImagemBannerInstitucional(formulario, endereco);
            }
        });
    });
}

function atualizarImagemBannerInstitucional(formulario, endereco) {
    const editor = formulario.querySelector('[data-banner-posicao="0"]');
    const imagem = editor?.querySelector('[data-previa-imagem]');

    if (!imagem) return;

    if (endereco) {
        imagem.src = endereco;
        imagem.classList.remove('hidden');
    } else {
        imagem.removeAttribute('src');
        imagem.classList.add('hidden');
    }
}

function restaurarImagensUsuario(formulario, usuario) {
    for (const campo of [
        'logo',
        'favicon',
        'imagem_compartilhamento',
        'banner_institucional_imagem'
    ]) {
        const imagem = formulario.querySelector(
            `[data-imagem-previa="${campo}"]`
        );

        const vazio = formulario.querySelector(
            `[data-imagem-vazia="${campo}"]`
        );

        const caminho = String(usuario[campo] ?? '').trim();

        if (!imagem) continue;

        if (caminho) {
            const endereco = /^https?:\/\//i.test(caminho)
                ? caminho
                : `${window.location.origin}/${caminho.replace(/^\/+/, '')}`;

            imagem.src = endereco;
            imagem.classList.remove('hidden');

            if (vazio) vazio.classList.add('hidden');

            if (campo === 'banner_institucional_imagem') {
                atualizarImagemBannerInstitucional(formulario, endereco);
            }
        } else {
            imagem.removeAttribute('src');
            imagem.classList.add('hidden');

            if (vazio) vazio.classList.remove('hidden');
        }
    }

    for (const campo of ['cor_principal', 'cor_secundaria']) {
        const entrada = formulario.querySelector(`[data-cor-site="${campo}"]`);
        const cor = String(usuario[campo] ?? '');

        if (entrada && /^#[0-9a-f]{6}$/i.test(cor)) {
            entrada.value = cor;
        }
    }
}

function atualizarPreviaBanner(editor) {
    const ler = nome =>
        editor.querySelector(`[data-banner-texto="${nome}"]`)?.value ?? '';

    const usarCores = editor.querySelector('[data-banner-usar-cores]')?.checked;

    const formulario = editor.closest('#cadastro-usuario-form');

    const corPrincipal = formulario?.querySelector(
        '[data-cor-site="cor_principal"]'
    )?.value ?? '#4D6775';

    const corSecundaria = formulario?.querySelector(
        '[data-cor-site="cor_secundaria"]'
    )?.value ?? '#4D6775';

    const cores = usarCores
        ? [corPrincipal, corSecundaria, corSecundaria]
        : [1, 2, 3].map(numero =>
            editor.querySelector(`[data-banner-cor="${numero}"]`)?.value ?? '#30343B'
        );

    const previa = editor.querySelector('[data-banner-previa]');

    if (previa) {
        previa.style.background = `linear-gradient(115deg, ${cores.join(', ')})`;
    }

    for (const [campo, seletor] of Object.entries({
        texto_pequeno: '[data-previa-texto-pequeno]',
        titulo: '[data-previa-titulo]',
        descricao: '[data-previa-descricao]',
        texto_botao: '[data-previa-botao]',
    })) {
        const destino = editor.querySelector(seletor);

        if (destino) destino.textContent = ler(campo);
    }

    const imagem = editor.querySelector('[data-previa-imagem]');
    const produto = editor.querySelector('[data-banner-produto]');

    if (imagem && !produto) {
        const formulario = editor.closest('#cadastro-usuario-form');
        const imagemInstitucional = formulario?.querySelector(
            '[data-imagem-previa="banner_institucional_imagem"]'
        );

        const endereco = imagemInstitucional?.getAttribute('src') ?? '';

        if (endereco) {
            imagem.src = endereco;
            imagem.classList.remove('hidden');
        } else {
            imagem.removeAttribute('src');
            imagem.classList.add('hidden');
        }
    }

    if (imagem && produto) {
        const opcao = produto.selectedOptions[0];
        const endereco = opcao?.dataset.imagem ?? '';

        if (endereco) {
            if (imagem.getAttribute('src') !== endereco) {
                imagem.src = endereco;
            }

            imagem.classList.remove('hidden');
        } else {
            imagem.removeAttribute('src');
            imagem.classList.add('hidden');
        }
    }
}

function atualizarProdutosDisponiveisBanners(formulario) {
    const removidos = new Set(
        Array.from(formulario.querySelectorAll('[data-produto-exibir]'))
            .filter(campo => !campo.checked)
            .map(campo => campo.value)
    );

    formulario.querySelectorAll('[data-banner-produto]').forEach(select => {
        for (const opcao of select.options) {
            if (!opcao.value) continue;

            opcao.hidden = removidos.has(opcao.value);
            opcao.disabled = removidos.has(opcao.value);
        }

        if (removidos.has(select.value)) {
            select.value = '';

            const editor = select.closest('[data-banner-editor]');
            const ativo = editor?.querySelector('[data-banner-ativo]');

            if (ativo) ativo.checked = false;

            if (editor) atualizarPreviaBanner(editor);
        }
    });
}

function configurarBanners(formulario) {
    formulario.querySelectorAll('[data-banner-editor]').forEach(editor => {
        editor.addEventListener('input', () => atualizarPreviaBanner(editor));
        editor.addEventListener('change', () => atualizarPreviaBanner(editor));

        editor.querySelectorAll('[data-banner-paleta]').forEach(botao => {
            botao.addEventListener('click', () => {
                const cores = botao.dataset.bannerPaleta.split(',');

                cores.forEach((cor, indice) => {
                    const campo = editor.querySelector(
                        `[data-banner-cor="${indice + 1}"]`
                    );

                    if (campo) campo.value = cor;
                });

                const usarCores = editor.querySelector('[data-banner-usar-cores]');

                if (usarCores) usarCores.checked = false;

                atualizarPreviaBanner(editor);
            });
        });

        editor.querySelector('[data-banner-produto]')?.addEventListener('change', evento => {
            const opcao = evento.target.selectedOptions[0];

            if (!opcao?.value) return;

            for (const [campo, atributo] of Object.entries({
                texto_pequeno: 'textoPequeno',
                titulo: 'titulo',
                descricao: 'descricao',
                texto_botao: 'textoBotao',
            })) {
                const destino = editor.querySelector(`[data-banner-texto="${campo}"]`);
                const valor = opcao.dataset[atributo];

                if (destino && valor) destino.value = valor;
            }

            atualizarPreviaBanner(editor);
        });

        const imagem = editor.querySelector('[data-previa-imagem]');

        if (imagem) {
            imagem.addEventListener('error', () => {
                imagem.classList.add('hidden');
            });
        }

        atualizarPreviaBanner(editor);
    });

    formulario.querySelector('[data-banners-cores-padrao]')?.addEventListener('click', () => {
        formulario.querySelectorAll('[data-banner-editor]').forEach(editor => {
            const usarCores = editor.querySelector('[data-banner-usar-cores]');

            if (usarCores) usarCores.checked = true;

            atualizarPreviaBanner(editor);
        });
    });

    formulario.querySelectorAll('[data-produto-exibir]').forEach(campo => {
        campo.addEventListener('change', () => {
            atualizarProdutosDisponiveisBanners(formulario);
        });
    });

    formulario.querySelectorAll('[data-produtos-acao]').forEach(botao => {
        botao.addEventListener('click', () => {
            atualizarProdutosDisponiveisBanners(formulario);
        });
    });

    atualizarProdutosDisponiveisBanners(formulario);
}

function restaurarBanners(formulario, banners) {
    for (const banner of banners ?? []) {
        const editor = formulario.querySelector(
            `[data-banner-posicao="${banner.posicao}"]`
        );

        if (!editor) continue;

        const tipoEsperado = Number(banner.posicao) === 0
            ? 'boas_vindas'
            : 'produto';

        if (banner.tipo !== tipoEsperado) continue;

        const ativo = editor.querySelector('[data-banner-ativo]');
        const usarCores = editor.querySelector('[data-banner-usar-cores]');
        const produto = editor.querySelector('[data-banner-produto]');

        if (ativo) ativo.checked = Number(banner.ativo) === 1;
        if (usarCores) usarCores.checked = Number(banner.usar_cores_site) === 1;
        if (produto) produto.value = banner.produto_id ?? '';

        for (const campo of [
            'texto_pequeno', 'titulo', 'descricao', 'texto_botao'
        ]) {
            const entrada = editor.querySelector(`[data-banner-texto="${campo}"]`);

            if (entrada) entrada.value = banner[campo] ?? '';
        }

        for (const numero of [1, 2, 3]) {
            const entrada = editor.querySelector(`[data-banner-cor="${numero}"]`);
            const cor = banner[`cor_${numero}`];

            if (entrada && /^#[0-9a-f]{6}$/i.test(cor ?? '')) {
                entrada.value = cor;
            }
        }

        atualizarPreviaBanner(editor);
    }

    atualizarProdutosDisponiveisBanners(formulario);
}

document.addEventListener('click', evento => {
    const botao = evento.target.closest('[data-aba-usuario]');

    if (!botao) return;

    const formulario = botao.closest('#cadastro-usuario-form');

    if (formulario) {
        ativarAbaUsuario(formulario, botao.dataset.abaUsuario);
    }
});

// Cadastro de usuários: página independente ou modal
const modalUsuario = document.getElementById('modal-usuario');
const abrirModalUsuario = document.getElementById('abrir-modal-usuario');
const fecharModalUsuario = document.getElementById('fechar-modal-usuario');
const conteudoModalUsuario = document.getElementById('modal-usuario-conteudo');

let aplicativoCadastro = null;
let cadastroSalvando = false;
let modalCarregando = false;
let elementoFocoAnterior = null;

function iniciarCadastroUsuario() {
    const elemento = document.getElementById('cadastro-usuario-vue');
    const formulario = document.getElementById('cadastro-usuario-form');

    if (!elemento || !formulario) return;

    aplicativoCadastro = createApp({
        setup() {
            const salvando = ref(false);
            const mensagem = ref('');
            const sucesso = ref(false);
            const erros = ref([]);
            const modoDemo = formulario.dataset.demoMode === '1';

            function bloquearEnterDemo(evento) {
                if (modoDemo && evento.key === 'Enter') {
                    evento.preventDefault();
                }
            }

            async function salvarUsuario(evento) {
                evento.preventDefault();
                if (modoDemo) return;

                if (salvando.value) return;

                salvando.value = true;
                cadastroSalvando = true;
                mensagem.value = '';
                sucesso.value = false;
                erros.value = [];

                let usuarioFoiSalvo = false;

                try {
                    const dadosFormulario = new FormData(formulario);

                    if (formulario.dataset.usuarioId) {
                        dadosFormulario.set('_method', 'PUT');
                    }

                    const resposta = await fetch(formulario.action, {
                        method: 'POST',
                        body: dadosFormulario,
                        credentials: 'same-origin',
                        headers: {
                            'Accept': 'application/json',
                            'X-Requested-With': 'XMLHttpRequest',
                        },
                    });

                    const dados = await resposta.json();

                    if (!resposta.ok) {
                        if (dados.demo_readonly) {
                            const idioma = navigator.language.toLowerCase();

                            if (idioma.startsWith('es')) {
                                mensagem.value = 'Acción no permitida. Esta cuenta está en modo de demostración y solo permite visualizar la información. No es posible guardar cambios.';
                            } else if (idioma.startsWith('en')) {
                                mensagem.value = 'Action not allowed. This account is in demo mode and allows viewing information only. Saving changes is disabled.';
                            } else {
                                mensagem.value = 'Ação não permitida. Esta conta está em modo demonstração e permite apenas visualizar as informações. Não é possível salvar alterações.';
                            }
                        } else if (resposta.status === 422 && dados.errors) {
                            erros.value = Object.values(dados.errors).flat();
                            mensagem.value = 'Verifique os dados informados.';
                        } else {
                            mensagem.value = dados.message || 'Não foi possível salvar o usuário.';
                        }

                        return;
                    }

                    usuarioFoiSalvo = true;
                    sucesso.value = true;
                    mensagem.value = dados.mensagem || 'Usuário cadastrado com sucesso.';

                    if (!formulario.dataset.usuarioId) {
                        formulario.dataset.usuarioId = String(dados.id);
                        formulario.action = formulario.dataset.updateUrl.replace(
                            '__ID__',
                            encodeURIComponent(String(dados.id))
                        );

                        const tituloModal = document.getElementById('modal-usuario-titulo');

                        if (tituloModal) {
                            tituloModal.textContent = `Editar usuário #${dados.id}`;
                        }

                        habilitarEdicaoUsuario(formulario);
                    }

                    if (modalUsuario) {
                        try {
                            await atualizarListagemUsuarios();
                        } catch (erro) {
                            mensagem.value = 'Usuário cadastrado com sucesso, mas não foi possível atualizar a lista. Atualize a listagem quando necessário.';
                            return;
                        }
                    }

                } catch (erro) {
                    mensagem.value = usuarioFoiSalvo
                        ? 'Usuário cadastrado, mas ocorreu um erro ao atualizar a tela.'
                        : 'Erro de comunicação com o servidor. Verifique a listagem antes de tentar novamente.';
                } finally {
                    salvando.value = false;
                    cadastroSalvando = false;
                }

                // Mantém o formulário aberto após salvar.
                // O próximo salvamento atualizará o usuário existente.
            }

            onMounted(() => {
                formulario.addEventListener('submit', salvarUsuario);
                formulario.addEventListener('keydown', bloquearEnterDemo);
            });

            onUnmounted(() => {
                formulario.removeEventListener('submit', salvarUsuario);
                formulario.removeEventListener('keydown', bloquearEnterDemo);
            });

            return () => h('div', [
                mensagem.value
                    ? h('div', {
                        role: 'status',
                        class: sucesso.value
                            ? 'mb-4 rounded-lg border border-green-300 bg-green-50 p-4 text-sm text-green-800'
                            : 'mb-4 rounded-lg border border-red-300 bg-red-50 p-4 text-sm text-red-800',
                    }, [
                        h('p', mensagem.value),
                        ...erros.value.map(erro =>
                            h('p', { class: 'mt-1' }, erro)
                        ),
                    ])
                    : null,

                h('div', { class: 'flex justify-end' }, [
                    h('button', {
                        type: 'submit',
                        disabled: modoDemo || salvando.value,
                        class: modoDemo
                            ? 'cursor-not-allowed rounded-lg bg-gray-300 px-5 py-2 text-sm font-semibold text-gray-500 opacity-70'
                            : 'cursor-pointer rounded-lg bg-gray-900 px-5 py-2 text-sm font-semibold text-white hover:bg-gray-700 disabled:cursor-not-allowed disabled:opacity-50',
                    }, salvando.value ? 'Salvando...' : 'Salvar usuário'),
                ]),
            ]);
        },
    });

    aplicativoCadastro.mount(elemento);
    configurarSelecaoProdutos(formulario);
    configurarBanners(formulario);
    configurarImagensUsuario(formulario);
}

// Atualiza a tabela, o total de registros e a paginação
// sem recarregar a página inteira.
async function atualizarListagemUsuarios() {
    const resposta = await fetch(window.location.href, {
        credentials: 'same-origin',
        headers: {
            'Accept': 'text/html',
            'X-Requested-With': 'XMLHttpRequest',
        },
    });

    if (!resposta.ok) {
        throw new Error('Não foi possível atualizar a listagem.');
    }

    const html = await resposta.text();
    const documento = new DOMParser().parseFromString(html, 'text/html');

    const novaListagem = documento.getElementById('listagem-usuarios');
    const novoTotal = documento.getElementById('total-usuarios');

    const listagemAtual = document.getElementById('listagem-usuarios');
    const totalAtual = document.getElementById('total-usuarios');

    if (!novaListagem || !novoTotal || !listagemAtual || !totalAtual) {
        throw new Error('A listagem atualizada não foi encontrada.');
    }

    listagemAtual.replaceWith(novaListagem);
    totalAtual.textContent = novoTotal.textContent;
}

// Fecha o modal e remove a instância Vue do formulário
function fecharModalCadastro() {
    if (!modalUsuario || cadastroSalvando || modalCarregando) return;

    if (aplicativoCadastro) {
        aplicativoCadastro.unmount();
        aplicativoCadastro = null;
    }

    conteudoModalUsuario.replaceChildren();

    modalUsuario.classList.add('hidden');
    modalUsuario.classList.remove('flex');
    modalUsuario.setAttribute('aria-hidden', 'true');

    document.body.style.overflow = '';

    if (elementoFocoAnterior instanceof HTMLElement) {
        elementoFocoAnterior.focus();
    }
}

// Carrega o formulário existente dentro do modal
async function abrirModalCadastro() {
    if (!modalUsuario || modalCarregando || !modalUsuario.classList.contains('hidden')) {
        return;
    }

    modalCarregando = true;
    elementoFocoAnterior = document.activeElement;

    document.getElementById('modal-usuario-titulo').textContent = 'Novo usuário';

    modalUsuario.classList.remove('hidden');
    modalUsuario.classList.add('flex');
    modalUsuario.setAttribute('aria-hidden', 'false');

    document.body.style.overflow = 'hidden';
    fecharModalUsuario.focus();

    conteudoModalUsuario.textContent = 'Carregando formulário...';

    try {
        const resposta = await fetch(abrirModalUsuario.dataset.url, {
            credentials: 'same-origin',
            headers: {
                'Accept': 'text/html',
                'X-Requested-With': 'XMLHttpRequest',
            },
        });

        if (!resposta.ok) {
            throw new Error('Não foi possível carregar o formulário.');
        }

        const html = await resposta.text();
        const documento = new DOMParser().parseFromString(html, 'text/html');
        const formulario = documento.getElementById('cadastro-usuario-form');

        if (!formulario) {
            throw new Error('O formulário de cadastro não foi encontrado.');
        }

        // O modal já possui um botão de fechar.
        const botaoVoltar = formulario.querySelector('a[href]');

        if (botaoVoltar) {
            botaoVoltar.parentElement.remove();
        }

        conteudoModalUsuario.replaceChildren(formulario);

        iniciarCadastroUsuario();

        formulario.querySelector('input[name="nome"]')?.focus();

    } catch (erro) {
        conteudoModalUsuario.textContent =
            'Não foi possível carregar o formulário. Feche o modal e tente novamente.';
    } finally {
        modalCarregando = false;
    }
}

// Abre o cadastro existente no mesmo modal.
async function abrirModalEdicao(url) {
    if (!modalUsuario || !modalUsuario.classList.contains('hidden')) return;

    await abrirModalCadastro();

    const formulario = document.getElementById('cadastro-usuario-form');

    if (!formulario) return;

    modalCarregando = true;
    conteudoModalUsuario.style.opacity = '0.5';

    try {
        const resposta = await fetch(url, {
            credentials: 'same-origin',
            headers: {
                'Accept': 'application/json',
                'X-Requested-With': 'XMLHttpRequest',
            },
        });

        if (!resposta.ok) {
            throw new Error('Não foi possível carregar o usuário.');
        }

        const dados = await resposta.json();
        const usuario = dados.usuario;

        if (!usuario || !usuario.id) {
            throw new Error('Dados do usuário inválidos.');
        }

        for (const nome of [
            'nome', 'email', 'whatsapp', 'titulo_site', 'dominio',
            'razao_social', 'documento', 'email_comercial',
            'telefone', 'cep', 'endereco', 'numero',
            'complemento', 'bairro', 'cidade', 'uf',
            'sobre_nos', 'descricao_site',
            'instagram', 'facebook', 'linkedin', 'youtube', 'tiktok'
        ]) {
            const campo = formulario.elements.namedItem(nome);

            if (campo) {
                campo.value = usuario[nome] ?? '';
            }
        }

        restaurarSelecaoProdutos(formulario, usuario);
        restaurarBanners(formulario, dados.banners);
        restaurarImagensUsuario(formulario, usuario);

        formulario.querySelectorAll('[data-banner-editor]').forEach(editor => {
            atualizarPreviaBanner(editor);
        });

        formulario.dataset.usuarioId = String(usuario.id);
        formulario.action = formulario.dataset.updateUrl.replace(
            '__ID__',
            encodeURIComponent(String(usuario.id))
        );

        document.getElementById('modal-usuario-titulo').textContent =
            `Editar usuário #${usuario.id}`;

        habilitarEdicaoUsuario(formulario);

        formulario.querySelector('input[name="nome"]')?.focus();

    } catch (erro) {
        alert(erro.message);
    } finally {
        conteudoModalUsuario.style.opacity = '';
        modalCarregando = false;
    }
}

// Mantém o cadastro funcionando na página independente
if (!modalUsuario) {
    iniciarCadastroUsuario();
}

// Ativa o modal na página de listagem
if (modalUsuario && abrirModalUsuario && fecharModalUsuario && conteudoModalUsuario) {
    abrirModalUsuario.addEventListener('click', abrirModalCadastro);

    document.addEventListener('click', function (evento) {
        const botao = evento.target.closest('.editar-usuario');

        if (botao) {
            abrirModalEdicao(botao.dataset.url);
        }
    });

    fecharModalUsuario.addEventListener('click', fecharModalCadastro);

    modalUsuario.addEventListener('click', function (evento) {
        if (evento.target === modalUsuario) {
            fecharModalCadastro();
        }
    });

    document.addEventListener('keydown', function (evento) {
        if (modalUsuario.classList.contains('hidden')) return;

        if (evento.key === 'Escape') {
            evento.preventDefault();
            fecharModalCadastro();
        }

        if (evento.key === 'Tab') {
            const elementos = Array.from(
                modalUsuario.querySelectorAll(
                    'button:not([disabled]), input:not([disabled]), select:not([disabled]), textarea:not([disabled]), a[href]'
                )
            ).filter(elemento => elemento.getClientRects().length > 0);

            if (!elementos.length) return;

            const primeiro = elementos[0];
            const ultimo = elementos[elementos.length - 1];

            if (evento.shiftKey && document.activeElement === primeiro) {
                evento.preventDefault();
                ultimo.focus();
            } else if (!evento.shiftKey && document.activeElement === ultimo) {
                evento.preventDefault();
                primeiro.focus();
            }
        }
    });
}

// Catálogo público: pesquisa, categorias e paginação de 9 serviços.
const catalogoPublico = document.querySelector('[data-catalogo]');

if (catalogoPublico) {
    const pesquisa = catalogoPublico.querySelector('[data-pesquisa-servicos]');
    const filtros = Array.from(
        catalogoPublico.querySelectorAll('[data-filtros-servicos] [data-categoria]')
    );
    const servicos = Array.from(catalogoPublico.querySelectorAll('[data-servico]'));
    const paginacao = catalogoPublico.querySelector('[data-paginacao-servicos]');
    const vazio = catalogoPublico.querySelector('[data-servicos-vazio]');
    const status = catalogoPublico.querySelector('[data-status-servicos]');

    let categoriaAtiva = '';
    let paginaAtual = 1;

    const porPagina = 9;

    function normalizarTexto(texto) {
        return String(texto ?? '')
            .normalize('NFD')
            .replace(/[\u0300-\u036f]/g, '')
            .toLocaleLowerCase('pt-BR');
    }

    function criarBotaoPagina(texto, pagina, ativo = false) {
        const botao = document.createElement('button');

        botao.type = 'button';
        botao.textContent = texto;
        botao.className = ativo
            ? 'min-w-10 rounded-lg bg-gray-900 px-3 py-2 text-sm font-bold text-white'
            : 'min-w-10 rounded-lg border border-gray-200 bg-white px-3 py-2 text-sm font-semibold text-gray-700 hover:bg-gray-100';

        if (ativo) {
            botao.setAttribute('aria-current', 'page');
        }

        botao.addEventListener('click', () => {
            paginaAtual = pagina;
            atualizarCatalogo();
            catalogoPublico.scrollIntoView({ behavior: 'smooth', block: 'start' });
        });

        return botao;
    }

    function atualizarCatalogo() {
        const termo = normalizarTexto(pesquisa?.value.trim());

        const encontrados = servicos.filter(servico => {
            const categoria = servico.dataset.categoria ?? '';
            const conteudo = normalizarTexto(servico.dataset.pesquisa);

            return (!categoriaAtiva || categoria === categoriaAtiva)
                && (!termo || conteudo.includes(termo));
        });

        const totalPaginas = Math.max(1, Math.ceil(encontrados.length / porPagina));
        paginaAtual = Math.min(paginaAtual, totalPaginas);

        const inicio = (paginaAtual - 1) * porPagina;
        const visiveis = new Set(encontrados.slice(inicio, inicio + porPagina));

        servicos.forEach(servico => {
            servico.classList.toggle('hidden', !visiveis.has(servico));
        });

        vazio?.classList.toggle('hidden', encontrados.length !== 0);

        filtros.forEach(botao => {
            const ativo = botao.dataset.categoria === categoriaAtiva;

            botao.setAttribute('aria-pressed', String(ativo));
            botao.classList.toggle('bg-gray-900', ativo);
            botao.classList.toggle('text-white', ativo);
            botao.classList.toggle('border-gray-900', ativo);
            botao.classList.toggle('bg-gray-50', !ativo);
            botao.classList.toggle('text-gray-700', !ativo);
            botao.classList.toggle('border-gray-200', !ativo);
        });

        paginacao.replaceChildren();

        if (totalPaginas > 1) {
            if (paginaAtual > 1) {
                paginacao.append(criarBotaoPagina('Anterior', paginaAtual - 1));
            }

            for (let pagina = 1; pagina <= totalPaginas; pagina++) {
                paginacao.append(
                    criarBotaoPagina(String(pagina), pagina, pagina === paginaAtual)
                );
            }

            if (paginaAtual < totalPaginas) {
                paginacao.append(criarBotaoPagina('Próxima', paginaAtual + 1));
            }
        }

        status.textContent = encontrados.length
            ? `Exibindo ${inicio + 1} a ${Math.min(inicio + porPagina, encontrados.length)} de ${encontrados.length} serviços.`
            : 'Nenhum serviço corresponde à pesquisa.';
    }

    pesquisa?.addEventListener('input', () => {
        paginaAtual = 1;
        atualizarCatalogo();
    });

    filtros.forEach(botao => {
        botao.addEventListener('click', () => {
            categoriaAtiva = botao.dataset.categoria ?? '';
            paginaAtual = 1;
            atualizarCatalogo();
        });
    });

    atualizarCatalogo();
}