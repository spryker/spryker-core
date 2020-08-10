import { ChangeDetectionStrategy, Component, Input, ViewEncapsulation } from '@angular/core';

@Component({
    selector: "mp-layout-main",
    templateUrl: "./layout-main.component.html",
    styleUrls: ["./layout-main.component.less"],
    changeDetection: ChangeDetectionStrategy.OnPush,
    encapsulation: ViewEncapsulation.None,
    host: {
        class: "mp-layout-main",
    },
})
export class LayoutMainComponent {
    @Input() navigationConfig = "";

    isCollapsed = false;

    updateCollapseHandler(isCollapsed: boolean): void {
        this.isCollapsed = isCollapsed;
    }
}
