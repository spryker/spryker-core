import { ChangeDetectionStrategy, Component, Input, ViewEncapsulation } from '@angular/core';
import { ToJson } from '@spryker/utils';
import { ImageSets, ImageData, ImageSetNames, ImageSetTitles } from './types';
import { IconPlusModule } from '@spryker/icon/icons';
import { IconDeleteModule } from '../../icons';
import { EmptyImageSet, EmptyImageSetData } from './empty-image-set';

@Component({
    selector: 'mp-image-sets',
    templateUrl: './image-sets.component.html',
    styleUrls: ['./image-sets.component.less'],
    changeDetection: ChangeDetectionStrategy.OnPush,
    encapsulation: ViewEncapsulation.None,
    host: {
        class: 'mp-image-sets',
    },
})
export class ImageSetsComponent {
    @Input() @ToJson() imageSets?: ImageSets[];
    @Input() @ToJson() names?: ImageSetNames;
    @Input() @ToJson() titles?: ImageSetTitles;

    addButtonIcon = IconPlusModule.icon;
    removeButtonIcon = IconDeleteModule.icon;

    addImageSet(): void {
        this.imageSets = [new EmptyImageSet(), ...this.imageSets];
    }

    removeImageSet(setIndex: number): void {
        this.imageSets = this.imageSets.filter((item, index) => index !== setIndex);
    }

    addImageSetData(setIndex: number): void {
        this.imageSets[setIndex] = { ...this.imageSets[setIndex] };
        this.imageSets[setIndex].images = [...this.imageSets[setIndex].images, new EmptyImageSetData()];
    }

    removeImageSetData(setIndex: number, imageIndex: number): void {
        this.imageSets[setIndex] = { ...this.imageSets[setIndex] };
        this.imageSets[setIndex].images = this.imageSets[setIndex].images.filter((item, index) => index !== imageIndex);
    }

    trackByImageSet(index: number, set: ImageSets): string {
        return `${index}-${set?.idProductImageSet}-${set?.originalIndex}`;
    }

    trackByImageSetImages(index: number, images: ImageData): string {
        return `${index}-${images?.idProductImage}`;
    }
}
