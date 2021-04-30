import { NgModule } from '@angular/core';
import { AjaxActionModule } from '@spryker/ajax-action';
import { AjaxPostActionCloseService } from '@spryker/ajax-action.post-action.close-overlay';
import { AjaxPostActionRedirectService } from '@spryker/ajax-action.post-action.redirect';
import { AjaxPostActionRefreshTableService } from '@spryker/ajax-action.post-action.refresh-table';
import { AjaxPostActionRefreshDrawerService } from '@spryker/ajax-action.post-action.refresh-drawer';
import { AjaxPostActionRefreshParentTableService } from '@spryker/ajax-action.post-action.refresh-parent-table';

@NgModule({
    imports: [
        AjaxActionModule.withActions({
            close_overlay: AjaxPostActionCloseService,
            redirect: AjaxPostActionRedirectService,
            refresh_table: AjaxPostActionRefreshTableService,
            refresh_drawer: AjaxPostActionRefreshDrawerService,
            refresh_parent_table: AjaxPostActionRefreshParentTableService,
        }),
    ],
})
export class DefaultAjaxActionConfigModule {}
