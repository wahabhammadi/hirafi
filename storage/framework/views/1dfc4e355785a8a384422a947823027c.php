<?php if (isset($component)) { $__componentOriginal9ac128a9029c0e4701924bd2d73d7f54 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal9ac128a9029c0e4701924bd2d73d7f54 = $attributes; } ?>
<?php $component = App\View\Components\AppLayout::resolve([] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? (array) $attributes->getIterator() : [])); ?>
<?php $component->withName('app-layout'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag && $constructor = (new ReflectionClass(App\View\Components\AppLayout::class))->getConstructor()): ?>
<?php $attributes = $attributes->except(collect($constructor->getParameters())->map->getName()->all()); ?>
<?php endif; ?>
<?php $component->withAttributes([]); ?>
     <?php $__env->slot('header', null, []); ?> 
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                <?php echo e(__('لوحة التحكم')); ?>

            </h2>
            <?php if(auth()->user()->role === 'craftsman'): ?>
            <a href="<?php echo e(route('profile.edit')); ?>" class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white tracking-widest hover:bg-blue-700 focus:bg-blue-700 active:bg-blue-800 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                <?php echo e(__('تعديل الملف الشخصي')); ?>

            </a>
            <?php endif; ?>
        </div>
     <?php $__env->endSlot(); ?>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <?php if(session('status')): ?>
                <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-4" role="alert">
                    <p><?php echo e(session('status')); ?></p>
                </div>
            <?php endif; ?>

            <?php if(auth()->user()->role === 'craftsman'): ?>
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <!-- إحصائيات الحرفي -->
                    <h3 class="text-lg font-medium mb-4"><?php echo e(__('إحصائيات عامة')); ?></h3>
                    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-5 gap-4 mb-6">
                        <div class="bg-blue-50 dark:bg-blue-900/30 rounded-lg p-4 text-center">
                            <div class="text-3xl font-bold text-blue-600 dark:text-blue-400 mb-1"><?php echo e($stats->total_offers ?? 0); ?></div>
                            <div class="text-sm text-gray-600 dark:text-gray-400"><?php echo e(__('إجمالي العروض')); ?></div>
                        </div>
                        <div class="bg-yellow-50 dark:bg-yellow-900/30 rounded-lg p-4 text-center">
                            <div class="text-3xl font-bold text-yellow-600 dark:text-yellow-400 mb-1"><?php echo e($stats->pending_offers ?? 0); ?></div>
                            <div class="text-sm text-gray-600 dark:text-gray-400"><?php echo e(__('عروض قيد الانتظار')); ?></div>
                        </div>
                        <div class="bg-green-50 dark:bg-green-900/30 rounded-lg p-4 text-center">
                            <div class="text-3xl font-bold text-green-600 dark:text-green-400 mb-1"><?php echo e($stats->accepted_offers ?? 0); ?></div>
                            <div class="text-sm text-gray-600 dark:text-gray-400"><?php echo e(__('عروض مقبولة')); ?></div>
                        </div>
                        <div class="bg-red-50 dark:bg-red-900/30 rounded-lg p-4 text-center">
                            <div class="text-3xl font-bold text-red-600 dark:text-red-400 mb-1"><?php echo e($stats->rejected_offers ?? 0); ?></div>
                            <div class="text-sm text-gray-600 dark:text-gray-400"><?php echo e(__('عروض مرفوضة')); ?></div>
                        </div>
                        <div class="bg-purple-50 dark:bg-purple-900/30 rounded-lg p-4 text-center">
                            <div class="flex justify-center mb-1">
                                <div class="text-3xl font-bold text-purple-600 dark:text-purple-400">
                                    <?php echo e(number_format($stats->avg_rating ?? 0, 1)); ?>

                                </div>
                                <div class="text-xl text-yellow-500 mr-1 mt-1">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                        <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                                    </svg>
                                </div>
                            </div>
                            <div class="text-sm text-gray-600 dark:text-gray-400"><?php echo e(__('متوسط التقييم')); ?></div>
                        </div>
                    </div>
                    
                    <!-- روابط سريعة للحرفي -->
                    <h3 class="text-lg font-medium mb-4"><?php echo e(__('روابط سريعة')); ?></h3>
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
                        <a href="<?php echo e(route('craftsman.offres.index')); ?>" class="block p-6 bg-blue-50 dark:bg-blue-900/30 rounded-lg hover:bg-blue-100 dark:hover:bg-blue-900/50 transition duration-150">
                                <div class="flex items-center">
                                    <div class="p-3 bg-blue-500 text-white rounded-full">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                                        </svg>
                                    </div>
                                    <div class="mr-4">
                                        <h4 class="text-lg font-semibold text-gray-900 dark:text-gray-100"><?php echo e(__('جميع عروضي')); ?></h4>
                                        <p class="text-sm text-gray-600 dark:text-gray-300"><?php echo e(__('عرض وإدارة جميع العروض التي قدمتها')); ?></p>
                                    </div>
                                </div>
                            </a>
                            
                        <a href="<?php echo e(route('craftsman.portfolio.index')); ?>" class="block p-6 bg-purple-50 dark:bg-purple-900/30 rounded-lg hover:bg-purple-100 dark:hover:bg-purple-900/50 transition duration-150">
                                <div class="flex items-center">
                                <div class="p-3 bg-purple-500 text-white rounded-full">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                        </svg>
                                    </div>
                                    <div class="mr-4">
                                    <h4 class="text-lg font-semibold text-gray-900 dark:text-gray-100"><?php echo e(__('ملف الأعمال')); ?></h4>
                                    <p class="text-sm text-gray-600 dark:text-gray-300"><?php echo e(__('إدارة ملف الأعمال الخاص بك')); ?></p>
                                    </div>
                                </div>
                            </a>
                            
                        <a href="<?php echo e(route('craftsman.ratings')); ?>" class="block p-6 bg-yellow-50 dark:bg-yellow-900/30 rounded-lg hover:bg-yellow-100 dark:hover:bg-yellow-900/50 transition duration-150">
                                <div class="flex items-center">
                                <div class="p-3 bg-yellow-500 text-white rounded-full">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z" />
                                        </svg>
                                    </div>
                                    <div class="mr-4">
                                    <h4 class="text-lg font-semibold text-gray-900 dark:text-gray-100"><?php echo e(__('تقييماتي')); ?></h4>
                                    <p class="text-sm text-gray-600 dark:text-gray-300"><?php echo e(__('عرض تقييمات العملاء لخدماتك')); ?></p>
                                    </div>
                                </div>
                            </a>
                    </div>
                        </div>
                    </div>

                    <!-- المشاريع المتاحة التي تناسب تخصصك -->
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                        <h3 class="text-lg font-medium mb-4"><?php echo e(__('المشاريع المتاحة التي تناسب تخصصك')); ?></h3>
                        
                    <!-- Buscador de proyectos por provincia -->
                    <?php if(auth()->user()->role === 'admin' || auth()->user()->role === 'craftsman'): ?>
                    <div class="border border-green-200 dark:border-green-800 bg-green-50 dark:bg-green-900/20 rounded-lg p-3 mb-4">
                        <h5 class="font-semibold text-sm mb-2 flex items-center text-green-700 dark:text-green-300">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 ml-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                            </svg>
                            البحث عن مشاريع في ولاية محددة:
                        </h5>
                        <form action="<?php echo e(route('dashboard')); ?>" method="GET" class="flex items-center space-x-2 rtl:space-x-reverse">
                            <select id="province" name="province" class="text-sm rounded border-gray-300 dark:border-gray-600 dark:bg-gray-700 flex-grow">
                                <option value=""><?php echo e(__('كل الولايات (عرض جميع المشاريع حسب التخصص فقط)')); ?></option>
                                <?php $__currentLoopData = \App\Helpers\AlgerianProvinces::getProvinces(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $code => $name): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <option value="<?php echo e($code); ?>" <?php echo e((request('province') == $code) ? 'selected' : ''); ?>>
                                        <?php echo e($name); ?> (<?php echo e($code); ?>)
                                    </option>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </select>
                            <button type="submit" class="px-4 py-2 bg-green-500 text-white text-sm rounded hover:bg-green-600 transition-colors">
                                بحث
                            </button>
                        </form>
                    </div>
                    <?php endif; ?>
                    
                    <!-- Proyectos principales en formato de tabla -->
                        <?php if(isset($matchingProjects) && $matchingProjects->count() > 0): ?>
                    <div class="overflow-hidden shadow-sm sm:rounded-lg bg-white dark:bg-gray-800 mt-4">
                                <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                            <thead class="bg-gray-50 dark:bg-gray-700">
                                        <tr>
                                            <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                                <?php echo e(__('عنوان المشروع')); ?>

                                            </th>
                                            <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                                <?php echo e(__('الميزانية')); ?>

                                            </th>
                                            <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                        <?php echo e(__('الولاية')); ?>

                                            </th>
                                            <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                                <?php echo e(__('تاريخ النشر')); ?>

                                            </th>
                                            <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                                <?php echo e(__('خيارات')); ?>

                                            </th>
                                        </tr>
                                    </thead>
                            <tbody class="bg-white divide-y divide-gray-200 dark:bg-gray-800 dark:divide-gray-700">
                                        <?php $__currentLoopData = $matchingProjects; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $project): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <tr class="<?php echo e($project->address == auth()->user()->address ? 'bg-green-50 dark:bg-green-900/20' : ''); ?> hover:bg-gray-50 dark:hover:bg-gray-700/50">
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700 dark:text-gray-300">
                                                    <?php echo e($project->titre); ?>

                                                </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700 dark:text-gray-300">
                                            <?php echo e(number_format($project->budget, 2)); ?> <?php echo e($project->currency_symbol); ?>

                                                </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700 dark:text-gray-300">
                                            <?php echo e($project->province_name); ?>

                                                </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700 dark:text-gray-300">
                                                    <?php echo e($project->created_at->format('Y-m-d')); ?>

                                                </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm">
                                            <a href="<?php echo e(route('commandes.show', $project)); ?>" class="text-blue-600 hover:text-blue-900 dark:text-blue-400 dark:hover:text-blue-300 ml-2">
                                                        <?php echo e(__('عرض التفاصيل')); ?>

                                                    </a>
                                            <?php if(auth()->user()->role === 'craftsman'): ?>
                                            <a href="<?php echo e(route('offres.create', $project)); ?>" class="text-green-600 hover:text-green-900 dark:text-green-400 dark:hover:text-green-300">
                                                <?php echo e(__('تقديم عرض')); ?>

                                            </a>
                                            <?php endif; ?>
                                                </td>
                                            </tr>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    </tbody>
                                </table>
                        <div class="p-4">
                                <?php echo e($matchingProjects->links()); ?>

                        </div>
                            </div>
                        <?php else: ?>
                    <div class="bg-yellow-50 dark:bg-yellow-900/20 p-6 rounded-lg text-center mt-4">
                        <p class="text-yellow-700 dark:text-yellow-300 mb-2 font-semibold"><?php echo e(__('لا توجد حاليا مشاريع مناسبة لتخصصك')); ?></p>
                        <p class="text-sm text-yellow-600 dark:text-yellow-400"><?php echo e(__('حاول تغيير معايير البحث أو تحقق لاحقًا من وجود مشاريع جديدة')); ?></p>
                            </div>
                        <?php endif; ?>
                    
                    <?php if(isset($specialtyOnlyProjects) && $specialtyOnlyProjects->count() > 0 && request('province')): ?>
                    <hr class="my-4 border-yellow-300 dark:border-yellow-700">
                    <h4 class="font-semibold mb-3"><?php echo e(__('مشاريع أخرى في تخصصك:')); ?></h4>
                    <div class="overflow-hidden shadow-sm sm:rounded-lg bg-white dark:bg-gray-800">
                        <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                            <thead class="bg-gray-50 dark:bg-gray-700">
                                <tr>
                                    <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                        <?php echo e(__('عنوان المشروع')); ?>

                                    </th>
                                    <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                        <?php echo e(__('الميزانية')); ?>

                                    </th>
                                    <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                        <?php echo e(__('الولاية')); ?>

                                    </th>
                                    <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                        <?php echo e(__('تاريخ النشر')); ?>

                                    </th>
                                    <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                        <?php echo e(__('خيارات')); ?>

                                    </th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200 dark:bg-gray-800 dark:divide-gray-700">
                                <?php $__currentLoopData = $specialtyOnlyProjects; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $project): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <tr class="<?php echo e($project->address == auth()->user()->address ? 'bg-green-50 dark:bg-green-900/20' : ''); ?> hover:bg-gray-50 dark:hover:bg-gray-700/50">
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700 dark:text-gray-300">
                                            <?php echo e($project->titre); ?>

                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700 dark:text-gray-300">
                                            <?php echo e(number_format($project->budget, 2)); ?> <?php echo e($project->currency_symbol); ?>

                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700 dark:text-gray-300">
                                            <?php echo e($project->province_name); ?>

                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700 dark:text-gray-300">
                                            <?php echo e($project->created_at->format('Y-m-d')); ?>

                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm">
                                            <a href="<?php echo e(route('commandes.show', $project)); ?>" class="text-blue-600 hover:text-blue-900 dark:text-blue-400 dark:hover:text-blue-300 ml-2">
                                                <?php echo e(__('عرض التفاصيل')); ?>

                                            </a>
                                            <?php if(auth()->user()->role === 'craftsman'): ?>
                                            <a href="<?php echo e(route('offres.create', $project)); ?>" class="text-green-600 hover:text-green-900 dark:text-green-400 dark:hover:text-green-300">
                                                <?php echo e(__('تقديم عرض')); ?>

                                            </a>
                                            <?php endif; ?>
                                        </td>
                                    </tr>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </tbody>
                        </table>
                    </div>
                    <?php endif; ?>
                </div>
            </div>
            <?php elseif(auth()->user()->role === 'client'): ?>
            <!-- محتوى خاص بالعميل -->
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <!-- إحصائيات العميل -->
                    <h3 class="text-lg font-medium mb-4"><?php echo e(__('إحصائيات عامة')); ?></h3>
                    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-4 mb-6">
                        <div class="bg-blue-50 dark:bg-blue-900/30 rounded-lg p-4 text-center">
                            <div class="text-3xl font-bold text-blue-600 dark:text-blue-400 mb-1"><?php echo e($stats->total_projects ?? 0); ?></div>
                            <div class="text-sm text-gray-600 dark:text-gray-400"><?php echo e(__('إجمالي المشاريع')); ?></div>
                        </div>
                        <div class="bg-yellow-50 dark:bg-yellow-900/30 rounded-lg p-4 text-center">
                            <div class="text-3xl font-bold text-yellow-600 dark:text-yellow-400 mb-1"><?php echo e($stats->pending_projects ?? 0); ?></div>
                            <div class="text-sm text-gray-600 dark:text-gray-400"><?php echo e(__('مشاريع قيد الانتظار')); ?></div>
                        </div>
                        <div class="bg-green-50 dark:bg-green-900/30 rounded-lg p-4 text-center">
                            <div class="text-3xl font-bold text-green-600 dark:text-green-400 mb-1"><?php echo e($stats->in_progress_projects ?? 0); ?></div>
                            <div class="text-sm text-gray-600 dark:text-gray-400"><?php echo e(__('مشاريع قيد التنفيذ')); ?></div>
                        </div>
                        <div class="bg-purple-50 dark:bg-purple-900/30 rounded-lg p-4 text-center">
                            <div class="text-3xl font-bold text-purple-600 dark:text-purple-400 mb-1"><?php echo e($stats->completed_projects ?? 0); ?></div>
                            <div class="text-sm text-gray-600 dark:text-gray-400"><?php echo e(__('مشاريع مكتملة')); ?></div>
                        </div>
                    </div>
                    
                    <!-- روابط سريعة للعميل -->
                    <h3 class="text-lg font-medium mb-4"><?php echo e(__('روابط سريعة')); ?></h3>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
                        <a href="<?php echo e(route('commandes.index')); ?>" class="block p-6 bg-blue-50 dark:bg-blue-900/30 rounded-lg hover:bg-blue-100 dark:hover:bg-blue-900/50 transition duration-150">
                            <div class="flex items-center">
                                <div class="p-3 bg-blue-500 text-white rounded-full">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                                    </svg>
                                </div>
                                <div class="mr-4">
                                    <h4 class="text-lg font-semibold text-gray-900 dark:text-gray-100"><?php echo e(__('مشاريعي')); ?></h4>
                                    <p class="text-sm text-gray-600 dark:text-gray-300"><?php echo e(__('عرض وإدارة جميع المشاريع الخاصة بك')); ?></p>
                                </div>
                            </div>
                        </a>
                        
                        <a href="<?php echo e(route('commandes.create')); ?>" class="block p-6 bg-green-50 dark:bg-green-900/30 rounded-lg hover:bg-green-100 dark:hover:bg-green-900/50 transition duration-150">
                            <div class="flex items-center">
                                <div class="p-3 bg-green-500 text-white rounded-full">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                                    </svg>
                                </div>
                                <div class="mr-4">
                                    <h4 class="text-lg font-semibold text-gray-900 dark:text-gray-100"><?php echo e(__('إضافة مشروع')); ?></h4>
                                    <p class="text-sm text-gray-600 dark:text-gray-300"><?php echo e(__('إنشاء مشروع جديد للحصول على عروض')); ?></p>
                                </div>
                            </div>
                        </a>
                        
                        <a href="<?php echo e(route('client.offres.index')); ?>" class="block p-6 bg-yellow-50 dark:bg-yellow-900/30 rounded-lg hover:bg-yellow-100 dark:hover:bg-yellow-900/50 transition duration-150">
                            <div class="flex items-center">
                                <div class="p-3 bg-yellow-500 text-white rounded-full">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                                    </svg>
                                </div>
                                <div class="mr-4">
                                    <h4 class="text-lg font-semibold text-gray-900 dark:text-gray-100"><?php echo e(__('العروض الواردة')); ?></h4>
                                    <p class="text-sm text-gray-600 dark:text-gray-300"><?php echo e(__('تصفح وإدارة العروض المقدمة لمشاريعك')); ?></p>
                                </div>
                            </div>
                        </a>
                    </div>
                </div>
                    </div>
                    
            <!-- آخر المشاريع -->
            <?php if(isset($recentProjects) && $recentProjects->count() > 0): ?>
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <h3 class="text-lg font-medium mb-4"><?php echo e(__('آخر المشاريع')); ?></h3>
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                            <thead class="bg-gray-50 dark:bg-gray-800">
                                <tr>
                                    <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                        <?php echo e(__('عنوان المشروع')); ?>

                                    </th>
                                    <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                        <?php echo e(__('الميزانية')); ?>

                                    </th>
                                    <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                        <?php echo e(__('الولاية')); ?>

                                    </th>
                                    <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                        <?php echo e(__('الحالة')); ?>

                                    </th>
                                    <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                        <?php echo e(__('تاريخ النشر')); ?>

                                    </th>
                                    <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                        <?php echo e(__('خيارات')); ?>

                                    </th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200 dark:bg-gray-900 dark:divide-gray-700">
                                <?php $__currentLoopData = $recentProjects; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $project): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <tr class="bg-green-50 dark:bg-green-900/20">
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700 dark:text-gray-300">
                                            <?php echo e($project->titre); ?>

                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700 dark:text-gray-300">
                                            <?php echo e(number_format($project->budget, 2)); ?> دج
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700 dark:text-gray-300">
                                            <?php echo e($project->province_name); ?> 
                                            <?php if(auth()->user()->role === 'admin'): ?>
                                            <span class="text-xs text-gray-500">(<?php echo e($project->address); ?>)</span>
                                            <?php endif; ?>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm">
                                            <?php if($project->statue == 'pending'): ?>
                                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200">
                                                    <?php echo e(__('قيد الانتظار')); ?>

                                                </span>
                                            <?php elseif($project->statue == 'in_progress'): ?>
                                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200">
                                                    <?php echo e(__('قيد التنفيذ')); ?>

                                                </span>
                                            <?php elseif($project->statue == 'completed'): ?>
                                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200">
                                                    <?php echo e(__('مكتمل')); ?>

                                                </span>
                                            <?php endif; ?>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700 dark:text-gray-300">
                                            <?php echo e($project->created_at->format('Y-m-d')); ?>

                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm">
                                            <a href="<?php echo e(route('commandes.show', $project)); ?>" class="text-indigo-600 hover:text-indigo-900 dark:text-indigo-400 dark:hover:text-indigo-300 font-bold">
                                                <?php echo e(__('عرض التفاصيل')); ?>

                                            </a>
                                        </td>
                                    </tr>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <?php endif; ?>
            <?php elseif(auth()->user()->role === 'admin'): ?>
            <!-- محتوى خاص بالمدير -->
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <!-- إحصائيات المدير -->
                    <h3 class="text-lg font-medium mb-4"><?php echo e(__('إحصائيات النظام')); ?></h3>
                    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-4 mb-6">
                        <div class="bg-blue-50 dark:bg-blue-900/30 rounded-lg p-4 text-center">
                            <div class="text-3xl font-bold text-blue-600 dark:text-blue-400 mb-1"><?php echo e($stats->total_users ?? 0); ?></div>
                            <div class="text-sm text-gray-600 dark:text-gray-400"><?php echo e(__('إجمالي المستخدمين')); ?></div>
                        </div>
                        <div class="bg-yellow-50 dark:bg-yellow-900/30 rounded-lg p-4 text-center">
                            <div class="text-3xl font-bold text-yellow-600 dark:text-yellow-400 mb-1"><?php echo e($stats->total_craftsmen ?? 0); ?></div>
                            <div class="text-sm text-gray-600 dark:text-gray-400"><?php echo e(__('الحرفيين')); ?></div>
                        </div>
                        <div class="bg-green-50 dark:bg-green-900/30 rounded-lg p-4 text-center">
                            <div class="text-3xl font-bold text-green-600 dark:text-green-400 mb-1"><?php echo e($stats->total_clients ?? 0); ?></div>
                            <div class="text-sm text-gray-600 dark:text-gray-400"><?php echo e(__('العملاء')); ?></div>
                        </div>
                        <div class="bg-purple-50 dark:bg-purple-900/30 rounded-lg p-4 text-center">
                            <div class="text-3xl font-bold text-purple-600 dark:text-purple-400 mb-1"><?php echo e($stats->total_projects ?? 0); ?></div>
                            <div class="text-sm text-gray-600 dark:text-gray-400"><?php echo e(__('المشاريع')); ?></div>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
                        <div class="bg-indigo-50 dark:bg-indigo-900/30 rounded-lg p-4 text-center">
                            <div class="text-3xl font-bold text-indigo-600 dark:text-indigo-400 mb-1"><?php echo e($stats->total_offers ?? 0); ?></div>
                            <div class="text-sm text-gray-600 dark:text-gray-400"><?php echo e(__('إجمالي العروض')); ?></div>
                        </div>
                        <div class="bg-orange-50 dark:bg-orange-900/30 rounded-lg p-4 text-center">
                            <div class="text-3xl font-bold text-orange-600 dark:text-orange-400 mb-1"><?php echo e($stats->pending_offers ?? 0); ?></div>
                            <div class="text-sm text-gray-600 dark:text-gray-400"><?php echo e(__('العروض قيد الانتظار')); ?></div>
                        </div>
                        <div class="bg-teal-50 dark:bg-teal-900/30 rounded-lg p-4 text-center">
                            <div class="text-3xl font-bold text-teal-600 dark:text-teal-400 mb-1"><?php echo e($stats->accepted_offers ?? 0); ?></div>
                            <div class="text-sm text-gray-600 dark:text-gray-400"><?php echo e(__('العروض المقبولة')); ?></div>
                    </div>
                    </div>
                    
                    <!-- روابط سريعة للمدير -->
                    <h3 class="text-lg font-medium mb-4"><?php echo e(__('روابط سريعة')); ?></h3>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
                        <a href="<?php echo e(route('admin.offres.index')); ?>" class="block p-6 bg-blue-50 dark:bg-blue-900/30 rounded-lg hover:bg-blue-100 dark:hover:bg-blue-900/50 transition duration-150">
                            <div class="flex items-center">
                                <div class="p-3 bg-blue-500 text-white rounded-full">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                                    </svg>
                                </div>
                                <div class="mr-4">
                                    <h4 class="text-lg font-semibold text-gray-900 dark:text-gray-100"><?php echo e(__('إدارة العروض')); ?></h4>
                                    <p class="text-sm text-gray-600 dark:text-gray-300"><?php echo e(__('عرض وإدارة العروض المقدمة من الحرفيين')); ?></p>
                                </div>
                            </div>
                        </a>
                        
                        <a href="<?php echo e(route('commandes.index')); ?>" class="block p-6 bg-green-50 dark:bg-green-900/30 rounded-lg hover:bg-green-100 dark:hover:bg-green-900/50 transition duration-150">
                            <div class="flex items-center">
                                <div class="p-3 bg-green-500 text-white rounded-full">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                                    </svg>
                                </div>
                                <div class="mr-4">
                                    <h4 class="text-lg font-semibold text-gray-900 dark:text-gray-100"><?php echo e(__('إدارة المشاريع')); ?></h4>
                                    <p class="text-sm text-gray-600 dark:text-gray-300"><?php echo e(__('عرض وإدارة المشاريع في النظام')); ?></p>
                                </div>
                            </div>
                        </a>
                        
                        <a href="<?php echo e(route('admin.offres.export')); ?>" class="block p-6 bg-purple-50 dark:bg-purple-900/30 rounded-lg hover:bg-purple-100 dark:hover:bg-purple-900/50 transition duration-150">
                            <div class="flex items-center">
                                <div class="p-3 bg-purple-500 text-white rounded-full">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                    </svg>
                                </div>
                                <div class="mr-4">
                                    <h4 class="text-lg font-semibold text-gray-900 dark:text-gray-100"><?php echo e(__('تصدير البيانات')); ?></h4>
                                    <p class="text-sm text-gray-600 dark:text-gray-300"><?php echo e(__('تصدير تقارير وبيانات النظام')); ?></p>
                                </div>
                            </div>
                        </a>
                    </div>
                </div>
            </div>
            
            <!-- آخر النشاطات -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900 dark:text-gray-100">
                        <h3 class="text-lg font-medium mb-4"><?php echo e(__('آخر المشاريع')); ?></h3>
                        <?php if(isset($recentProjects) && $recentProjects->count() > 0): ?>
                            <div class="space-y-3">
                                <?php $__currentLoopData = $recentProjects; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $project): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <div class="border border-gray-200 dark:border-gray-700 rounded-lg p-4">
                                        <div class="flex justify-between items-start">
                                            <div>
                                                <h4 class="font-semibold text-gray-900 dark:text-gray-100"><?php echo e($project->titre); ?></h4>
                                                <p class="text-sm text-gray-600 dark:text-gray-400"><?php echo e(Str::limit($project->description, 100)); ?></p>
                                                <div class="mt-2">
                                                    <span class="text-sm text-gray-500 dark:text-gray-400">
                                                        <?php echo e(__('العميل:')); ?> <?php echo e($project->user->name); ?>

                                                    </span>
                                                    <span class="mr-2 text-sm text-gray-500 dark:text-gray-400">
                                                        <?php echo e(__('الميزانية:')); ?> <?php echo e(number_format($project->budget, 2)); ?> دج
                                                    </span>
                                                </div>
                                            </div>
                                            <div>
                                                <?php if($project->statue == 'pending'): ?>
                                                    <span class="px-2 py-1 text-xs font-semibold rounded-full bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200">
                                                        <?php echo e(__('قيد الانتظار')); ?>

                                                    </span>
                                                <?php elseif($project->statue == 'in_progress'): ?>
                                                    <span class="px-2 py-1 text-xs font-semibold rounded-full bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200">
                                                        <?php echo e(__('قيد التنفيذ')); ?>

                                                    </span>
                                                <?php elseif($project->statue == 'completed'): ?>
                                                    <span class="px-2 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200">
                                                        <?php echo e(__('مكتمل')); ?>

                                                    </span>
                                                <?php endif; ?>
                                            </div>
                                        </div>
                                        <div class="mt-2 text-left">
                                            <a href="<?php echo e(route('commandes.show', $project)); ?>" class="text-indigo-600 hover:text-indigo-900 dark:text-indigo-400 dark:hover:text-indigo-300 text-sm">
                                                <?php echo e(__('عرض التفاصيل')); ?> &larr;
                                            </a>
                                        </div>
                                    </div>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </div>
                        <?php else: ?>
                            <div class="bg-gray-100 dark:bg-gray-700 p-4 rounded-lg text-center">
                                <p><?php echo e(__('لا توجد مشاريع حديثة.')); ?></p>
                            </div>
                        <?php endif; ?>
                    </div>
                    </div>
                    
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900 dark:text-gray-100">
                        <h3 class="text-lg font-medium mb-4"><?php echo e(__('آخر العروض')); ?></h3>
                        <?php if(isset($recentOffers) && $recentOffers->count() > 0): ?>
                            <div class="space-y-3">
                                <?php $__currentLoopData = $recentOffers; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $offer): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <div class="border border-gray-200 dark:border-gray-700 rounded-lg p-4">
                                        <div class="flex justify-between items-start">
                                            <div>
                                                <h4 class="font-semibold text-gray-900 dark:text-gray-100"><?php echo e($offer->commande->titre); ?></h4>
                                                <p class="text-sm text-gray-600 dark:text-gray-400"><?php echo e(__('عرض من:')); ?> <?php echo e($offer->craftsman->user->name); ?></p>
                                                <div class="mt-2">
                                                    <span class="text-sm text-gray-500 dark:text-gray-400">
                                                        <?php echo e(__('القيمة:')); ?> <?php echo e(number_format($offer->price, 2)); ?> دج
                                                    </span>
                                                    <span class="mr-2 text-sm text-gray-500 dark:text-gray-400">
                                                        <?php echo e(__('المدة:')); ?> <?php echo e($offer->duration); ?> <?php echo e(__('يوم')); ?>

                                                    </span>
                                                </div>
                                            </div>
                                            <div>
                                                <?php if($offer->status == 'pending'): ?>
                                                    <span class="px-2 py-1 text-xs font-semibold rounded-full bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200">
                                                        <?php echo e(__('قيد الانتظار')); ?>

                                                    </span>
                                                <?php elseif($offer->status == 'accepted'): ?>
                                                    <span class="px-2 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200">
                                                        <?php echo e(__('مقبول')); ?>

                                                    </span>
                                                <?php elseif($offer->status == 'rejected'): ?>
                                                    <span class="px-2 py-1 text-xs font-semibold rounded-full bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200">
                                                        <?php echo e(__('مرفوض')); ?>

                                                    </span>
                                                <?php endif; ?>
                                            </div>
                                        </div>
                                        <div class="mt-2 text-left">
                                            <a href="<?php echo e(route('admin.offres.show', $offer)); ?>" class="text-indigo-600 hover:text-indigo-900 dark:text-indigo-400 dark:hover:text-indigo-300 text-sm">
                                                <?php echo e(__('عرض التفاصيل')); ?> &larr;
                                            </a>
                                        </div>
                                    </div>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </div>
                        <?php else: ?>
                            <div class="bg-gray-100 dark:bg-gray-700 p-4 rounded-lg text-center">
                                <p><?php echo e(__('لا توجد عروض حديثة.')); ?></p>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
            <?php endif; ?>
        </div>
    </div>
 <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal9ac128a9029c0e4701924bd2d73d7f54)): ?>
<?php $attributes = $__attributesOriginal9ac128a9029c0e4701924bd2d73d7f54; ?>
<?php unset($__attributesOriginal9ac128a9029c0e4701924bd2d73d7f54); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal9ac128a9029c0e4701924bd2d73d7f54)): ?>
<?php $component = $__componentOriginal9ac128a9029c0e4701924bd2d73d7f54; ?>
<?php unset($__componentOriginal9ac128a9029c0e4701924bd2d73d7f54); ?>
<?php endif; ?>
<?php /**PATH C:\laragon\www\hirafi\resources\views/dashboard.blade.php ENDPATH**/ ?>