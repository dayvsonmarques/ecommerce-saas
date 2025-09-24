# Integrações Implementadas

Este documento descreve as integrações implementadas no sistema de e-commerce SAAS.

## 🚚 Integração com Correios

### Configuração

1. **Obter credenciais dos Correios:**
   - Acesse o site dos Correios
   - Registre-se para obter código da empresa e senha
   - Configure no arquivo `.env`:

```env
# Correios
CORREIOS_CODIGO_EMPRESA=seu_codigo_empresa
CORREIOS_SENHA=sua_senha
CORREIOS_CEP_ORIGEM=01310-100
```

### Funcionalidades

- ✅ Cálculo automático de frete
- ✅ Múltiplos serviços (SEDEX, PAC)
- ✅ Cálculo de dimensões do carrinho
- ✅ Fallback para casos de erro
- ✅ Interface responsiva para seleção de frete

### Serviços Disponíveis

- **SEDEX (04014)**: Entrega expressa (1-3 dias úteis)
- **PAC (04510)**: Encomenda econômica (3-7 dias úteis)

## 💳 Integração com Mercado Pago

### Configuração

1. **Criar conta no Mercado Pago:**
   - Acesse [mercadopago.com.br](https://mercadopago.com.br)
   - Crie uma conta de desenvolvedor
   - Obtenha suas credenciais

2. **Configurar no arquivo `.env`:**

```env
# Mercado Pago
MERCADOPAGO_ACCESS_TOKEN=seu_access_token
MERCADOPAGO_PUBLIC_KEY=sua_public_key
MERCADOPAGO_INTEGRATOR_ID=seu_integrator_id
MERCADOPAGO_SANDBOX=true
```

### Funcionalidades

- ✅ Criação de preferências de pagamento
- ✅ Processamento de pagamentos
- ✅ Webhook para atualização de status
- ✅ Múltiplos métodos de pagamento
- ✅ Interface de checkout integrada

### Métodos de Pagamento

- **Cartão de Crédito**: Visa, Mastercard, Elo, American Express
- **Cartão de Débito**: Visa, Mastercard, Elo
- **PIX**: Pagamento instantâneo
- **Boleto Bancário**: Pagamento em até 3 dias úteis

## 🔧 Configuração do Sistema

### 1. Instalar Dependências

```bash
composer install
npm install
```

### 2. Configurar Banco de Dados

```bash
php artisan migrate
php artisan db:seed
```

### 3. Compilar Assets

```bash
npm run build
```

### 4. Configurar Webhook do Mercado Pago

1. No painel do Mercado Pago, configure o webhook:
   - URL: `https://seudominio.com/mercadopago/webhook`
   - Eventos: `payment`

## 📱 Componentes Frontend

### ShippingCalculator.vue
- Calculadora de frete integrada
- Validação de CEP
- Seleção de opções de entrega
- Interface responsiva

### MercadoPagoCheckout.vue
- Checkout integrado com Mercado Pago
- Validação de dados do pagador
- Redirecionamento seguro
- Feedback visual

## 🛠️ APIs Disponíveis

### Frete
- `POST /store/shipping/calculate` - Calcular frete
- `POST /store/shipping/validate-cep` - Validar CEP

### Pagamento
- `POST /store/payment/create-preference` - Criar preferência
- `POST /store/payment/verify-status` - Verificar status
- `GET /store/payment/methods` - Obter métodos de pagamento
- `POST /mercadopago/webhook` - Webhook do Mercado Pago

## 🔒 Segurança

### CSRF Protection
- Token CSRF em todas as requisições
- Validação no backend

### Validação de Dados
- Validação de entrada em todos os endpoints
- Sanitização de dados
- Verificação de permissões

### Webhook Security
- Validação de origem do webhook
- Logs de todas as transações
- Tratamento de erros

## 📊 Monitoramento

### Logs
- Logs de todas as transações
- Logs de erros de integração
- Logs de webhooks

### Métricas
- Taxa de sucesso de pagamentos
- Tempo de resposta das APIs
- Erros de cálculo de frete

## 🚀 Deploy

### Variáveis de Ambiente de Produção

```env
# Correios (Produção)
CORREIOS_CODIGO_EMPRESA=seu_codigo_producao
CORREIOS_SENHA=sua_senha_producao

# Mercado Pago (Produção)
MERCADOPAGO_ACCESS_TOKEN=seu_token_producao
MERCADOPAGO_PUBLIC_KEY=sua_public_key_producao
MERCADOPAGO_SANDBOX=false
```

### Checklist de Deploy

- [ ] Configurar credenciais de produção
- [ ] Atualizar URLs de webhook
- [ ] Testar integrações em ambiente de produção
- [ ] Configurar monitoramento
- [ ] Backup do banco de dados

## 🆘 Troubleshooting

### Problemas Comuns

1. **Erro de Cálculo de Frete**
   - Verificar credenciais dos Correios
   - Verificar CEP de origem
   - Verificar dimensões dos produtos

2. **Erro de Pagamento**
   - Verificar credenciais do Mercado Pago
   - Verificar configuração do webhook
   - Verificar logs de erro

3. **Webhook não funciona**
   - Verificar URL do webhook
   - Verificar certificado SSL
   - Verificar logs do servidor

### Logs Importantes

```bash
# Logs do Laravel
tail -f storage/logs/laravel.log

# Logs de erro
grep "ERROR" storage/logs/laravel.log

# Logs de webhook
grep "webhook" storage/logs/laravel.log
```

## 📞 Suporte

Para suporte técnico:
- Email: suporte@loja.com
- Telefone: (11) 99999-9999
- Documentação: [docs.mercadopago.com.br](https://docs.mercadopago.com.br)
- Correios: [www.correios.com.br](https://www.correios.com.br)
