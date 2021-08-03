import { ImageSets, ImageData } from './types';

export class EmptyImageSetData implements ImageData {
    order: number;
    srcLarge = '';
    srcSmall = '';
}

export class EmptyImageSet implements ImageSets {
    name = '';
    images: EmptyImageSetData[] = [new EmptyImageSetData()];
}
