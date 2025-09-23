<template>
  <div class="min-h-screen bg-white dark:bg-gray-900 text-gray-900 dark:text-gray-100">
    <!-- Header -->
    <header class="sticky top-0 z-20 bg-white/80 dark:bg-gray-900/80 backdrop-blur border-b border-gray-200 dark:border-gray-800">
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 h-16 flex items-center justify-between">
        <div class="flex items-center gap-2">
          <span class="inline-flex h-9 w-9 items-center justify-center rounded bg-indigo-600 text-white font-bold">A</span>
          <span class="text-base font-semibold">Aluguel de Loja Online</span>
        </div>
          <div class="flex items-center gap-2">
            <Link :href="route('store.products.index')" class="px-3 py-1.5 rounded-md text-sm bg-gray-100 hover:bg-gray-200 dark:bg-gray-800 dark:hover:bg-gray-700">Loja</Link>
            <Link :href="route('login')" class="px-3 py-1.5 rounded-md text-sm bg-gray-100 hover:bg-gray-200 dark:bg-gray-800 dark:hover:bg-gray-700">Entrar</Link>
            <Link :href="route('register')" class="px-3 py-1.5 rounded-md text-sm bg-indigo-600 text-white hover:bg-indigo-700">Criar conta</Link>
          </div>
      </div>
    </header>

    <!-- Hero / Banner -->
    <section class="bg-gradient-to-b from-indigo-50 to-white dark:from-gray-900 dark:to-gray-900">
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16 lg:py-24 grid lg:grid-cols-2 gap-8 items-center">
        <div>
          <h1 class="text-3xl sm:text-4xl lg:text-5xl font-extrabold tracking-tight">Sua loja online profissional em minutos</h1>
          <p class="mt-4 text-lg text-gray-600 dark:text-gray-300">Plataforma SaaS completa para criar, gerenciar e escalar seu e-commerce. Sem complicações técnicas, com todos os recursos que você precisa para vender online.</p>
          <div class="mt-6 flex items-center gap-3">
            <Link :href="route('store.products.index')" class="inline-flex items-center rounded-md bg-indigo-600 px-5 py-2.5 text-white font-medium hover:bg-indigo-700">Ver Loja</Link>
            <a href="#servicos" class="inline-flex items-center rounded-md px-5 py-2.5 border border-gray-300 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-800">Ver recursos</a>
          </div>
        </div>
        <div class="relative">
          <div class="aspect-[16/10] w-full rounded-xl bg-indigo-100 dark:bg-gray-800 flex items-center justify-center">
            <span class="text-indigo-700 dark:text-indigo-300 font-semibold">Banner / Mockup da Loja</span>
          </div>
        </div>
      </div>
    </section>

    <!-- Introdução -->
    <section id="intro" class="py-12 sm:py-16">
      <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
        <h2 class="text-2xl sm:text-3xl font-bold">Por que escolher nossa plataforma SaaS?</h2>
        <p class="mt-4 text-gray-600 dark:text-gray-300">Custos baixos, fácil de usar, escalável e com atualizações automáticas. Tudo que você precisa para começar a vender online hoje mesmo, sem investimentos iniciais altos.</p>
      </div>
    </section>

    <!-- Serviços -->
    <section id="servicos" class="py-12 sm:py-16 bg-gray-50 dark:bg-gray-800">
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <h3 class="text-xl sm:text-2xl font-bold text-center">Recursos Completos para seu E-commerce</h3>
        <div class="mt-8 grid gap-6 sm:grid-cols-2 lg:grid-cols-3">
          <div class="p-6 rounded-lg bg-white dark:bg-gray-900 border border-gray-200 dark:border-gray-700">
            <div class="text-indigo-600 mb-3">💰</div>
            <h4 class="font-semibold">Custos Baixos</h4>
            <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">Sem investimentos iniciais altos. Pague apenas uma taxa mensal acessível e comece a vender imediatamente.</p>
          </div>
          <div class="p-6 rounded-lg bg-white dark:bg-gray-900 border border-gray-200 dark:border-gray-700">
            <div class="text-indigo-600 mb-3">🚀</div>
            <h4 class="font-semibold">Fácil de Usar</h4>
            <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">Interface intuitiva com drag-and-drop. Crie sua loja sem conhecimento técnico em minutos.</p>
          </div>
          <div class="p-6 rounded-lg bg-white dark:bg-gray-900 border border-gray-200 dark:border-gray-700">
            <div class="text-indigo-600 mb-3">📈</div>
            <h4 class="font-semibold">Escalável</h4>
            <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">Cresça automaticamente com seu negócio. Suporte a picos de tráfego sem preocupações.</p>
          </div>
          <div class="p-6 rounded-lg bg-white dark:bg-gray-900 border border-gray-200 dark:border-gray-700">
            <div class="text-indigo-600 mb-3">🔄</div>
            <h4 class="font-semibold">Atualizações Automáticas</h4>
            <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">Sempre com as últimas funcionalidades e segurança. Sem manutenção manual necessária.</p>
          </div>
          <div class="p-6 rounded-lg bg-white dark:bg-gray-900 border border-gray-200 dark:border-gray-700">
            <div class="text-indigo-600 mb-3">🌐</div>
            <h4 class="font-semibold">Acesso de Qualquer Lugar</h4>
            <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">Gerencie sua loja de qualquer lugar do mundo, a qualquer hora, via navegador web.</p>
          </div>
          <div class="p-6 rounded-lg bg-white dark:bg-gray-900 border border-gray-200 dark:border-gray-700">
            <div class="text-indigo-600 mb-3">🔒</div>
            <h4 class="font-semibold">Segurança Integrada</h4>
            <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">SSL, gateways de pagamento seguros e conformidade PCI DSS incluídos automaticamente.</p>
          </div>
        </div>
      </div>
    </section>

    <!-- Clientes -->
    <section id="clientes" class="py-12 sm:py-16">
      <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
        <h3 class="text-xl sm:text-2xl font-bold text-center">Milhares de lojistas já escolheram nossa plataforma</h3>
        <p class="mt-4 text-center text-gray-600 dark:text-gray-400">Junte-se a empreendedores que estão vendendo mais online com nossa solução SaaS</p>
        <div class="mt-8 grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-6 gap-6 items-center opacity-80">
          <div class="h-12 rounded bg-gray-100 dark:bg-gray-800 flex items-center justify-center text-sm font-medium text-gray-600 dark:text-gray-400">Loja A</div>
          <div class="h-12 rounded bg-gray-100 dark:bg-gray-800 flex items-center justify-center text-sm font-medium text-gray-600 dark:text-gray-400">Loja B</div>
          <div class="h-12 rounded bg-gray-100 dark:bg-gray-800 flex items-center justify-center text-sm font-medium text-gray-600 dark:text-gray-400">Loja C</div>
          <div class="h-12 rounded bg-gray-100 dark:bg-gray-800 flex items-center justify-center text-sm font-medium text-gray-600 dark:text-gray-400">Loja D</div>
          <div class="h-12 rounded bg-gray-100 dark:bg-gray-800 flex items-center justify-center text-sm font-medium text-gray-600 dark:text-gray-400">Loja E</div>
          <div class="h-12 rounded bg-gray-100 dark:bg-gray-800 flex items-center justify-center text-sm font-medium text-gray-600 dark:text-gray-400">Loja F</div>
        </div>
      </div>
    </section>

    <!-- Contato -->
    <section id="contato" class="py-12 sm:py-16 bg-gray-50 dark:bg-gray-800">
      <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8">
        <h3 class="text-xl sm:text-2xl font-bold text-center">Pronto para começar sua loja online?</h3>
        <p class="mt-4 text-center text-gray-600 dark:text-gray-400">Entre em contato conosco e descubra como nossa plataforma SaaS pode transformar seu negócio</p>
        <form @submit.prevent="submitContact" class="mt-8 grid grid-cols-1 gap-4">
          <div class="grid sm:grid-cols-2 gap-4">
            <input v-model="contact.name" type="text" placeholder="Seu nome" class="rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 px-3 py-2" />
            <input v-model="contact.email" type="email" placeholder="Seu email" class="rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 px-3 py-2" />
          </div>
          <input v-model="contact.company" type="text" placeholder="Nome da sua empresa (opcional)" class="rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 px-3 py-2" />
          <textarea v-model="contact.message" rows="4" placeholder="Conte-nos sobre seu projeto e como podemos ajudar..." class="rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 px-3 py-2"></textarea>
          <div class="flex justify-center">
            <button type="submit" class="inline-flex items-center rounded-md bg-indigo-600 px-8 py-3 text-white font-medium hover:bg-indigo-700">Solicitar Demonstração</button>
          </div>
        </form>
      </div>
    </section>

    <!-- Footer -->
    <footer class="py-8 border-t border-gray-200 dark:border-gray-800 text-center text-sm">
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        © {{ new Date().getFullYear() }} Aluguel de Loja Online. Todos os direitos reservados.
      </div>
    </footer>
  </div>
  
</template>

<script setup>
import { Link, router, useForm } from '@inertiajs/vue3'

const contact = useForm({ name: '', email: '', company: '', message: '' })
const submitContact = () => {
  // Placeholder: poderia enviar para endpoint de contato futuramente
  contact.reset()
  alert('Solicitação enviada! Nossa equipe entrará em contato em breve para agendar sua demonstração personalizada.')
}
</script>


