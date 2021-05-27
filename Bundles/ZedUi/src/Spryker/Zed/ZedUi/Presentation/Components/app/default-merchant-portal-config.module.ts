import { NgModule } from '@angular/core';
import { LocaleModule } from '@spryker/locale';
import { DeLocaleModule } from '@spryker/locale/locales/de';
import { EN_LOCALE, EnLocaleModule } from '@spryker/locale/locales/en';
import { NotificationModule } from '@spryker/notification';
import { DefaultContextSerializationModule } from '@spryker/utils';
import { WebComponentsModule } from '@spryker/web-components';
import { ModalModule } from '@spryker/modal';
import { DefaultTableConfigModule } from './table/default-table-config.module';
import { DefaultAjaxActionConfigModule } from './ajax-action/default-ajax-action-config.module';
import { DefaultUnsavedChangesConfigModule } from './unsaved-changes/default-unsaved-changes-config.module';
import { DefaultActionsConfigModule } from './actions/default-actions-config.module';

@NgModule({
    imports: [
        WebComponentsModule.forRoot(),
        LocaleModule.forRoot({ defaultLocale: EN_LOCALE }),
        EnLocaleModule,
        DeLocaleModule,
        DefaultTableConfigModule,
        NotificationModule.forRoot(),
        DefaultAjaxActionConfigModule,
        DefaultContextSerializationModule,
        DefaultUnsavedChangesConfigModule,
        DefaultActionsConfigModule,
        ModalModule.forRoot(),
    ],
})
export class DefaultMerchantPortalConfigModule {}
