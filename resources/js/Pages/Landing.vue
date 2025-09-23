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
          <h1 class="text-3xl sm:text-4xl lg:text-5xl font-extrabold tracking-tight">Seu e-commerce pronto em minutos</h1>
          <p class="mt-4 text-lg text-gray-600 dark:text-gray-300">Plataforma SAAS para você criar, gerenciar e vender online com facilidade. Sem complicações técnicas.</p>
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
        <h2 class="text-2xl sm:text-3xl font-bold">Tudo que você precisa para vender online</h2>
        <p class="mt-4 text-gray-600 dark:text-gray-300">Gerencie produtos, pedidos, clientes e estoque em um só lugar. Interface moderna, responsiva e com modo escuro.</p>
      </div>
    </section>

    <!-- Serviços -->
    <section id="servicos" class="py-12 sm:py-16 bg-gray-50 dark:bg-gray-800">
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <h3 class="text-xl sm:text-2xl font-bold text-center">Recursos</h3>
        <div class="mt-8 grid gap-6 sm:grid-cols-2 lg:grid-cols-3">
          <div class="p-6 rounded-lg bg-white dark:bg-gray-900 border border-gray-200 dark:border-gray-700">
            <div class="text-indigo-600 mb-3">🧾</div>
            <h4 class="font-semibold">Catálogo de Produtos</h4>
            <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">Cadastro completo de produtos, categorias e imagens.</p>
          </div>
          <div class="p-6 rounded-lg bg-white dark:bg-gray-900 border border-gray-200 dark:border-gray-700">
            <div class="text-indigo-600 mb-3">🛒</div>
            <h4 class="font-semibold">Pedidos e Clientes</h4>
            <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">Gerencie o ciclo do pedido e acompanhe seus clientes.</p>
          </div>
          <div class="p-6 rounded-lg bg-white dark:bg-gray-900 border border-gray-200 dark:border-gray-700">
            <div class="text-indigo-600 mb-3">🎨</div>
            <h4 class="font-semibold">Tema moderno + Dark Mode</h4>
            <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">Layout baseado no Tailwind e TailAdmin, responsivo e personalizável.</p>
          </div>
          <div class="p-6 rounded-lg bg-white dark:bg-gray-900 border border-gray-200 dark:border-gray-700">
            <div class="text-indigo-600 mb-3">📈</div>
            <h4 class="font-semibold">Dashboard</h4>
            <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">Acompanhe seus números e tome decisões informadas.</p>
          </div>
          <div class="p-6 rounded-lg bg-white dark:bg-gray-900 border border-gray-200 dark:border-gray-700">
            <div class="text-indigo-600 mb-3">🔒</div>
            <h4 class="font-semibold">Autenticação</h4>
            <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">Login, registro e proteção de rotas com Inertia + Laravel.</p>
          </div>
          <div class="p-6 rounded-lg bg-white dark:bg-gray-900 border border-gray-200 dark:border-gray-700">
            <div class="text-indigo-600 mb-3">⚙️</div>
            <h4 class="font-semibold">Extensível</h4>
            <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">APIs e componentes prontos para escalar seu negócio.</p>
          </div>
        </div>
      </div>
    </section>

    <!-- Clientes -->
    <section id="clientes" class="py-12 sm:py-16">
      <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
        <h3 class="text-xl sm:text-2xl font-bold text-center">Clientes que confiam</h3>
        <div class="mt-8 grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-6 gap-6 items-center opacity-80">
          <div class="h-12 rounded bg-gray-100 dark:bg-gray-800 flex items-center justify-center">Logo 1</div>
          <div class="h-12 rounded bg-gray-100 dark:bg-gray-800 flex items-center justify-center">Logo 2</div>
          <div class="h-12 rounded bg-gray-100 dark:bg-gray-800 flex items-center justify-center">Logo 3</div>
          <div class="h-12 rounded bg-gray-100 dark:bg-gray-800 flex items-center justify-center">Logo 4</div>
          <div class="h-12 rounded bg-gray-100 dark:bg-gray-800 flex items-center justify-center">Logo 5</div>
          <div class="h-12 rounded bg-gray-100 dark:bg-gray-800 flex items-center justify-center">Logo 6</div>
        </div>
      </div>
    </section>

    <!-- Contato -->
    <section id="contato" class="py-12 sm:py-16 bg-gray-50 dark:bg-gray-800">
      <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8">
        <h3 class="text-xl sm:text-2xl font-bold text-center">Fale com a gente</h3>
        <form @submit.prevent="submitContact" class="mt-8 grid grid-cols-1 gap-4">
          <div class="grid sm:grid-cols-2 gap-4">
            <input v-model="contact.name" type="text" placeholder="Seu nome" class="rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900" />
            <input v-model="contact.email" type="email" placeholder="Seu email" class="rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900" />
          </div>
          <input v-model="contact.company" type="text" placeholder="Empresa (opcional)" class="rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900" />
          <textarea v-model="contact.message" rows="4" placeholder="Mensagem" class="rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900"></textarea>
          <div class="flex justify-end">
            <button type="submit" class="inline-flex items-center rounded-md bg-indigo-600 px-5 py-2.5 text-white font-medium hover:bg-indigo-700">Enviar</button>
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
  alert('Mensagem enviada! Em breve entraremos em contato.')
}
</script>


