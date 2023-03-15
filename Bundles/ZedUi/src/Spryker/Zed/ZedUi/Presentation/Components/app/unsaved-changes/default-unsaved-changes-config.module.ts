import { NgModule } from '@angular/core';
import { UnsavedChangesModule } from '@spryker/unsaved-changes';
import { UnsavedChangesBrowserGuardModule } from '@spryker/unsaved-changes.guard.browser';
import { UnsavedChangesDrawerGuardModule } from '@spryker/unsaved-changes.guard.drawer';
import { UnsavedChangesGuardNavigationModule } from '@spryker/unsaved-changes.guard.navigation';

@NgModule({
    imports: [
        UnsavedChangesModule.forRoot(),
        UnsavedChangesDrawerGuardModule.forRoot(),
        UnsavedChangesGuardNavigationModule.forRoot(),
        UnsavedChangesBrowserGuardModule.forRoot(),
    ],
})
export class DefaultUnsavedChangesConfigModule {}
