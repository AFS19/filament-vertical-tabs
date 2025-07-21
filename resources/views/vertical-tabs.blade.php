<div
        x-data="{
        tab: @js($getChildComponentContainer()->getComponents()[0]->getId()),
        isNavOpen: false,
        tabs: [],
        currentIndex: 0,

        init() {
            // Get all tab IDs and store them
            this.tabs = Array.from(this.$el.querySelectorAll('[data-tab-id]')).map(el => el.dataset.tabId);

            // Set initial index
            this.currentIndex = this.tabs.indexOf(this.tab);

            // Watch for tab changes to update currentIndex
            this.$watch('tab', (tabId) => {
                this.currentIndex = this.tabs.indexOf(tabId);
            });
        },
    }"
        x-init="init()"
        class="filament-vertical-tabs relative"
>
    <!-- Mobile Header with Hamburger Button -->
    <div
            class="lg:hidden mb-2 flex justify-between items-center rounded-xl p-3"
    >
        <div class="flex items-center gap-2 font-medium">
            <template x-for="tab in $el.parentElement.querySelectorAll('[data-tab-id]')" :key="tab.dataset.tabId">
                <div x-show="tab.dataset.tabId === tab" class="flex items-center gap-2" style="display: none;">
                    <div x-html="tab.querySelector('[data-tab-icon]')?.innerHTML || ''" class="text-primary-500"></div>
                    <span x-text="tab.dataset.tabLabel" class="text-gray-900 dark:text-white"></span>
                </div>
            </template>
        </div>

        <button
                type="button"
                x-on:click="isNavOpen = !isNavOpen"
                class="inline-flex items-center p-2 w-10 h-10 justify-center text-sm text-gray-500 rounded-lg hover:bg-gray-100 dark:bg-gray-700 focus:outline-none ring-0 dark:text-gray-400 dark:hover:bg-gray-700"
                aria-controls="vertical-tabs-nav"
                :aria-expanded="isNavOpen"
        >
            <span class="sr-only">Toggle navigation</span>
            <svg
                    xmlns="http://www.w3.org/2000/svg"
                    class="w-5 h-5"
                    fill="none"
                    viewBox="0 0 24 24"
                    stroke="currentColor"
                    x-show="!isNavOpen"
            >
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
            </svg>
            <svg
                    xmlns="http://www.w3.org/2000/svg"
                    class="w-5 h-5"
                    fill="none"
                    viewBox="0 0 24 24"
                    stroke="currentColor"
                    x-show="isNavOpen"
                    style="display: none;"
            >
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
            </svg>
        </button>
    </div>

    <!-- Standard Mobile Navigation -->
    <div
            id="vertical-tabs-nav"
            x-show="isNavOpen"
            x-cloak
            class="lg:hidden mb-6 border-t border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-900"
            x-transition:enter="transition ease-out duration-200"
            x-transition:enter-start="opacity-0 transform -translate-y-2"
            x-transition:enter-end="opacity-100 transform translate-y-0"
            x-transition:leave="transition ease-in duration-150"
            x-transition:leave-start="opacity-100 transform translate-y-0"
            x-transition:leave-end="opacity-0 transform -translate-y-2"
            style="display: none;"
    >
        <div class="max-w-full px-4 py-2 mx-auto">
            <ul class="flex flex-col space-y-1 font-medium">
                @foreach ($getChildComponentContainer()->getComponents() as $tab)
                    <li>
                        <button
                                type="button"
                                x-on:click="tab = '{{ $tab->getId() }}'; isNavOpen = false"
                                class="flex items-center w-full gap-3 py-2 px-3 text-sm transition"
                                :class="{
                                'bg-primary-50 dark:bg-primary-500/20 text-primary-600 dark:text-primary-400 font-medium': tab === '{{ $tab->getId() }}',
                                'text-gray-900 dark:text-white': tab !== '{{ $tab->getId() }}'
                            }"
                        >
                            @if($tab->getIcon())
                                <span class="flex items-center justify-center w-7 h-7 rounded-lg"
                                      :class="{
                                    'bg-primary-100 dark:bg-primary-800/30 text-primary-600 dark:text-primary-400': tab === '{{ $tab->getId() }}',
                                    'text-gray-500 dark:text-gray-400': tab !== '{{ $tab->getId() }}'
                                }">
                                    <x-dynamic-component :component="$tab->getIcon()" class="h-5 w-5"/>
                                </span>
                            @endif
                            {{ $tab->getLabel() }}
                        </button>
                    </li>
                @endforeach
            </ul>
        </div>
    </div>

    <!-- Main Content Layout -->
    <div class="flex flex-col lg:flex-row gap-6">
        <!-- Vertical Navigation (hidden on mobile) -->
        <div class="hidden lg:block w-64 shrink-0">
            <div class="pr-4 rtl:pr-0 rtl:pl-4 sticky top-4">
                <div
                        class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden">
                    <h3 class="font-medium text-gray-900 dark:text-gray-100 p-4 border-b border-gray-200 dark:border-gray-700 text-sm sticky top-0 z-10 bg-white dark:bg-gray-800">
                        {{ $getLabel() ?? 'Navigation' }}
                    </h3>
                    <nav class="flex flex-col py-2 max-h-[calc(100vh-130px)] overflow-y-auto">
                        @foreach ($getChildComponentContainer()->getComponents() as $tab)
                            <div class="flex items-center mx-2 my-0.4">

                                <!-- Active indicator -->
                                <div
                                        x-show="tab === '{{ $tab->getId() }}'"
                                        class="w-1 self-stretch my-1 bg-primary-600 dark:bg-primary-500 rounded-r-full"
                                        style="display: none;"
                                        x-transition:enter="transition ease-in-out duration-200"
                                        x-transition:enter-start="opacity-0 transform -translate-x-1"
                                        x-transition:enter-end="opacity-100 transform translate-x-0"
                                ></div>

                                <!-- Tab button -->
                                <button
                                        type="button"
                                        x-on:click="tab = '{{ $tab->getId() }}'"
                                        class="flex items-center gap-1 px-4 py-3 mx-2 my-0.5 text-sm transition relative w-full"
                                        data-tab-id="{{ $tab->getId() }}"
                                        data-tab-label="{{ $tab->getLabel() }}"
                                        :class="{
                                    'text-primary-600 dark:text-primary-400 bg-primary-50 dark:bg-primary-500/20 font-medium': tab === '{{ $tab->getId() }}',
                                    'text-gray-400 dark:text-gray-300 hover:text-gray-700 dark:hover:text-primary-400 hover:bg-gray-50 dark:hover:bg-gray-800/50': tab !== '{{ $tab->getId() }}'
                                }"
                                >
                                    <!-- Tab icon -->
                                    @if($tab->getIcon())
                                        <span
                                                data-tab-icon
                                                class="flex items-center justify-center w-8 h-8 rounded-lg transition-colors shrink-0"
                                                :class="{
                                            'bg-primary-100 dark:bg-primary-800/30 text-primary-600 dark:text-primary-400': tab === '{{ $tab->getId() }}',
                                            'text-gray-500 dark:text-gray-400': tab !== '{{ $tab->getId() }}'
                                        }"
                                        >
                                        <x-dynamic-component :component="$tab->getIcon()" class="h-5 w-5"/>
                                    </span>
                                    @endif

                                    <!-- Tab label -->
                                    <span class="truncate">{{ $tab->getLabel() }}</span>
                                </button>
                            </div>
                        @endforeach
                    </nav>
                </div>
            </div>
        </div>

        <!-- Content Area -->
        <div class="flex-1 min-w-0">
            @foreach ($getChildComponentContainer()->getComponents() as $tab)
                <div
                        x-show="tab === '{{ $tab->getId() }}'"
                        x-cloak
                        x-transition:enter="transition ease-in-out duration-300"
                        x-transition:enter-start="opacity-0 transform translate-y-4"
                        x-transition:enter-end="opacity-100 transform translate-y-0"
                        style="display: none;"
                        class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-6"
                >
                    <div class="mb-4 border-b border-gray-200 dark:border-gray-700 pb-4 lg:hidden">
                        <h2 class="text-lg font-medium text-gray-900 dark:text-white flex items-center gap-3">
                            @if($tab->getIcon())
                                <span
                                        class="flex items-center justify-center w-8 h-8 rounded-lg bg-primary-100 dark:bg-primary-800/30 text-primary-600 dark:text-primary-400">
                                    <x-dynamic-component :component="$tab->getIcon()" class="h-5 w-5"/>
                                </span>
                            @endif
                            {{ $tab->getLabel() }}
                        </h2>
                    </div>

                    <div>
                        {{ $tab }}
                    </div>
                </div>
            @endforeach
        </div>
    </div>

    <!-- Scroll to top button -->
    <div
            x-data="{
            scrollToTop() {
                window.scrollTo({
                    top: 0,
                    behavior: 'smooth'
                });
            },
            isVisible: false,
            checkScroll() {
                this.isVisible = window.scrollY > 300;
            }
        }"
            x-init="
            window.addEventListener('scroll', () => checkScroll());
            checkScroll();
        "
            x-cloak
            class="fixed z-50"
            style="bottom: 4rem; right: 4rem;"
    >
        <button
                type="button"
                x-show="isVisible"
                x-on:click="scrollToTop"
                x-transition:enter="transition ease-out duration-300"
                x-transition:enter-start="opacity-0 transform translate-y-4"
                x-transition:enter-end="opacity-100 transform translate-y-0"
                x-transition:leave="transition ease-in duration-200"
                x-transition:leave-start="opacity-100 transform translate-y-0"
                x-transition:leave-end="opacity-0 transform translate-y-4"
                class="bg-primary-600 hover:bg-primary-700 text-white rounded-full p-3 shadow-lg focus:outline-none focus:ring-2 focus:ring-primary-300 dark:focus:ring-primary-800 transition-all"
                aria-label="Scroll to top"
        >
            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 10l7-7m0 0l7 7m-7-7v18" />
            </svg>
        </button>
    </div>
</div>
