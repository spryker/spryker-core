import { NgModule } from '@angular/core';
import { DrawerActionHandlerService, DrawerActionModule } from '@spryker/actions.drawer';
import { AjaxFormComponent, AjaxFormModule } from '@spryker/ajax-form';
import { ActionsModule } from '@spryker/actions';

declare module '@spryker/actions' {
    interface ActionsRegistry {
        drawer: DrawerActionHandlerService;
    }
}

declare module '@spryker/actions.drawer' {
    interface DrawerActionComponentsRegistry {
        'ajax-form': AjaxFormComponent;
    }
}

@NgModule({
    imports: [
        ActionsModule.withActions({ drawer: DrawerActionHandlerService }),
        DrawerActionModule.withComponents({ 'ajax-form': AjaxFormComponent }),
        AjaxFormModule,
    ],
})
export class DefaultActionsConfigModule {}
