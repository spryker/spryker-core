import { NgModule } from '@angular/core';
import { CommonModule } from '@angular/common';
import { LocaleModule } from '@spryker/locale';
import { LayoutFooterComponent } from './layout-footer.component';

@NgModule({
    imports: [CommonModule, LocaleModule],
    exports: [LayoutFooterComponent],
    declarations: [LayoutFooterComponent],
})
export class LayoutFooterModule {
}
