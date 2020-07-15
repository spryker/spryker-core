import { HttpClientModule } from "@angular/common/http";
import { Compiler, Injector, NgModule } from "@angular/core";
import { BrowserModule } from "@angular/platform-browser";
import { BrowserAnimationsModule } from "@angular/platform-browser/animations";
import { LocaleModule } from "@spryker/locale";
import { DeLocaleModule } from "@spryker/locale/locales/de";
import { EN_LOCALE, EnLocaleModule } from "@spryker/locale/locales/en";
import { WebComponentsModule } from "@spryker/web-components";

import { _getNgModules, ComponentsNgModule } from "./registry";
import { TableRootModule } from "./table/table-root.module";

@NgModule({
    imports: [
        BrowserModule,
        BrowserAnimationsModule,
        HttpClientModule,
        LocaleModule.forRoot({ defaultLocale: EN_LOCALE }),
        EnLocaleModule,
        DeLocaleModule,
        TableRootModule,
        WebComponentsModule.forRoot(),
    ],
    providers: [],
})
export class AppModule {
    constructor(private injector: Injector, private compiler: Compiler) {}

    ngDoBootstrap() {
        _getNgModules({
            notifyOnModule: (ngModule) => this.initComponentsModule(ngModule),
        }).forEach((ngModule) => this.initComponentsModule(ngModule));
    }

    private async initComponentsModule(ngModule: ComponentsNgModule) {
        const moduleFactory = await this.compiler.compileModuleAsync(ngModule);

        const moduleRef = moduleFactory.create(this.injector);

        moduleRef.instance.ngDoBootstrap();
    }
}
